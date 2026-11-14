<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEntityRequest;
use App\Http\Requests\Admin\UpdateEntityRequest;
use App\Models\Client;
use App\Models\Entity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EntityController extends Controller
{
    /** Common sub-entities offered as one-click shortcuts in the editor. */
    private const SUGGESTIONS = [
        'Alcaldía', 'Concejo', 'Personería', 'Servicios públicos', 'Regalías',
        'Gobernación', 'Asamblea', 'Contraloría', 'Hospital', 'Instituto de deportes',
    ];

    public function index(): View
    {
        return view('admin.entities.index', [
            'entities' => Entity::with(['client', 'links.client'])->withCount('links')->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return $this->form(new Entity);
    }

    public function store(StoreEntityRequest $request): RedirectResponse
    {
        $entity = DB::transaction(function () use ($request) {
            $entity = Entity::create($request->safe()->only(['name']));
            $this->saveEntityLogo($entity, $request);
            $this->syncLinks($entity, $request);

            return $entity;
        });

        return redirect()->route('admin.entities.edit', $entity)->with('status', 'Entidad creada.');
    }

    public function edit(Entity $entity): View
    {
        return $this->form($entity->load(['client', 'links.client']));
    }

    public function update(UpdateEntityRequest $request, Entity $entity): RedirectResponse
    {
        DB::transaction(function () use ($request, $entity) {
            $entity->update($request->safe()->only(['name']));
            $this->saveEntityLogo($entity, $request);
            $this->syncLinks($entity, $request);
        });

        return redirect()->route('admin.entities.edit', $entity)->with('status', 'Cambios guardados.');
    }

    public function destroy(Entity $entity): RedirectResponse
    {
        $entity->delete();

        return redirect()->route('admin.entities.index')->with('status', 'Entidad eliminada.');
    }

    private function form(Entity $entity): View
    {
        $old = old();
        $hasOld = ! empty($old);

        return view('admin.entities.form', [
            'entity' => $entity,
            'identity' => $this->logoState(
                $entity,
                $hasOld ? ($old['client_id'] ?? null) : $entity->client_id,
                $hasOld && ($old['remove_logo'] ?? false),
            ),
            'rows' => $this->formRows($entity),
            'clients' => Client::orderBy('name')->get(['id', 'name', 'img'])
                ->map(fn (Client $client) => ['id' => $client->id, 'name' => $client->name, 'img' => $client->img])
                ->all(),
            'suggestions' => self::SUGGESTIONS,
        ]);
    }

    private function saveEntityLogo(Entity $entity, StoreEntityRequest $request): void
    {
        $entity->update($this->resolveLogo(
            $entity->logo,
            $request->file('logo'),
            $request->validated('client_id'),
            $request->boolean('remove_logo'),
            'entities',
        ));
    }

    /**
     * Make the entity's links match the submitted rows: rows with a known id
     * are updated, rows without one are created, and links missing from the
     * submission are deleted. Order follows the submission order.
     */
    private function syncLinks(Entity $entity, StoreEntityRequest $request): void
    {
        $existing = $entity->links()->get()->keyBy('id');
        $keptIds = [];
        $order = 0;

        foreach ($request->validated('links', []) as $key => $row) {
            $link = isset($row['id']) ? $existing->get((int) $row['id']) : null;

            $attributes = [
                'name' => trim($row['name']),
                'href' => trim($row['href']),
                'order' => ++$order,
                ...$this->resolveLogo(
                    $link?->logo,
                    $request->file("links.$key.logo"),
                    $row['client_id'] ?? null,
                    (bool) ($row['remove_logo'] ?? false),
                    'entities/links',
                ),
            ];

            if ($link) {
                $link->update($attributes);
                $keptIds[] = $link->id;
            } else {
                $keptIds[] = $entity->links()->create($attributes)->id;
            }
        }

        $existing->except($keptIds)->each(function ($link) {
            $link->deleteLogoFile();
            $link->delete();
        });
    }

    /**
     * Decide the logo columns from what the form sent. A new upload wins,
     * then a chosen client; otherwise the current upload is kept unless it
     * was removed. A replaced or dropped upload is deleted from disk.
     *
     * @return array{client_id: int|null, logo: string|null}
     */
    private function resolveLogo(?string $currentLogo, ?UploadedFile $upload, mixed $clientId, bool $remove, string $directory): array
    {
        $logo = match (true) {
            $upload !== null => $upload->store($directory, 'public'),
            $clientId !== null, $remove => null,
            default => $currentLogo,
        };

        if ($currentLogo && $logo !== $currentLogo) {
            Storage::disk('public')->delete($currentLogo);
        }

        return [
            'client_id' => $upload === null && $clientId !== null ? (int) $clientId : null,
            'logo' => $logo,
        ];
    }

    /**
     * Logo state the editor starts from: the uploaded image (if any, and not
     * removed) and the chosen client.
     *
     * @return array{uploaded: string|null, clientId: int|null}
     */
    private function logoState(?\Illuminate\Database\Eloquent\Model $model, mixed $clientId, bool $removed): array
    {
        return [
            'uploaded' => $removed || ! $model?->logo ? null : Storage::disk('public')->url($model->logo),
            'clientId' => $clientId ? (int) $clientId : null,
        ];
    }

    /**
     * Rows for the Alpine-driven link editor. After a failed validation the
     * submitted rows are restored (uploads are lost, so existing logos are
     * looked up again by id).
     */
    private function formRows(Entity $entity): array
    {
        $links = $entity->exists ? $entity->links->keyBy('id') : collect();
        $old = old('links');

        if (is_array($old)) {
            return collect($old)->values()->map(function ($row) use ($links) {
                $link = isset($row['id']) ? $links->get((int) $row['id']) : null;

                return [
                    'id' => $link?->id,
                    'name' => $row['name'] ?? '',
                    'href' => $row['href'] ?? '',
                    ...$this->logoState($link, $row['client_id'] ?? null, (bool) ($row['remove_logo'] ?? false)),
                ];
            })->all();
        }

        return $links->values()->map(fn ($link) => [
            'id' => $link->id,
            'name' => $link->name,
            'href' => $link->href,
            ...$this->logoState($link, $link->client_id, false),
        ])->all();
    }
}
