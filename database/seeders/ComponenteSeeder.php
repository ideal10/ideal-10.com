<?php

namespace Database\Seeders;

use App\Models\Componente;
use Illuminate\Database\Seeder;

class ComponenteSeeder extends Seeder
{
    public function run(): void
    {
        $componentes = [
            [
                'slug' => 'financiero',
                'name' => 'Componente financiero',
                'body' => 'Presupuesto y contabilidad para seguimiento de recursos, ejecución y reportes.',
                'paths' => [
                    'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                ],
                'wide' => false,
                'content' => <<<'MD'
### Presupuesto
- Es una solución integral de software diseñada para la gestión presupuestal de entidades
territoriales. Este sistema proporciona un control detallado y efectivo del presupuesto,
cumpliendo con los requisitos legales y normativos permitiendo la programación y ejecución
eficiente de ingresos y gastos.
- Control y seguimiento del presupuesto por vigencia, registro y actualización del presupuesto
en unidades ejecutoras con división por secciones presupuestales, control de gastos de
inversión por proyectos. Integración de clasificadores presupuestales CCPET, CFF y CPI para
la programación y ejecución de ingresos y gastos, inclusión de CPC en la ejecución de gastos
de funcionamiento, inversión e ingresos, adaptabilidad a diferentes fases del ciclo presupuestal, la ejecución se desarrolla conservando la autonomía y control de los ejecutores
del gasto, la sumatoria de los ingresos y los gastos acumuladas todas las unidades ejecutoras
es el total del presupuesto.
- El diseño para la aplicación de los clasificadores esta diferenciado según el ciclo presupuestal
programación o ejecución de la siguiente manera.

![](../assets/img/presupuesto.png)

- Para la expedición del certificado de disponibilidad presupuestal para funcionamiento y servicio
a la deuda se utiliza el clasificador CCPET, para inversión se expide a nivel de proyecto.

- Generación de reportes para entes de control, informes CUIPO, Sistema General de Regalías,
CUIPO-AESGPRI, FUT-Registros Presupuestales y demás. Generación de reportes de
ejecución presupuestal con filtros personalizables por fecha, medio de pago, secciones
presupuestales, vigencias de gastos, fuentes, y creación de catálogos personalizados donde
el usuario tiene la opción de crear sus propios clasificadores seleccionando los rubros que
deseados; con flexibilidad para la visualización de ejecuciones de acuerdo con las
necesidades específicas, funcionalidad para exportar informes en formatos estándar (PDF,
Excel) con acceso fácil desde navegadores web e interfaz intuitiva y personalizable.

- Su capacidad para adaptarse a las necesidades específicas de cada entidad y su enfoque en
la transparencia y el cumplimiento normativo lo convierten en una herramienta indispensable
para la toma de decisiones financieras y el seguimiento preciso de los recursos.

### Contabilidad
- Registra la información aplicando ecuación contable, partida doble, y presenta el saldo con el signo resultado de sumar saldo inicial más débitos menos créditos, aplicando estas tres premisas la sumatoria del saldo final debe ser cero, (Conservación de ecuación contable) utiliza el catálogo de cuentas expedido por la Contaduría General de la nación (CGN) según la clasificación dada a la entidad, garantiza que el proceso contable sea la herramienta de control de la entidad, debe presentar la opción para que contabilidad tenga como función determinar si el bien o servicio adquirido debe ir como gasto o inversión, a stock de almacén, a un activo fijo o bien de uso público, una construcción en curso o maquinaria en montaje, este proceso debe estar seguidamente a la determinación del compromiso presupuestal, adicionalmente debe predeterminar las retenciones a aplicar en tesorería, con esto se garantiza que para todos los pagos que se realicen al contrato se apliquen las mismas condiciones en deducciones y registros contables.

- Los dígitos utilizados en las cuentas auxiliares son uniformes predefinidos previamente, la información es registrada en una sola línea de tiempo sin cortes para cierre de periodo estos se deben hacer con la utilización de registros contables determinando y trasladando resultados, las cuentas de balance no deben presentar cierres el corte lo determina el reporte generado, debe tener bloqueo de fecha de forma general para el registro de tipo administrativo y contable después del cierre de periodo.
MD,
            ],
            [
                'slug' => 'administrativo',
                'name' => 'Componente administrativo',
                'body' => 'Tesorería, gestión humana, activos y almacén para la operación institucional.',
                'paths' => [
                    'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                ],
                'wide' => false,
                'content' => <<<'MD'
### Tesorería

- Efectúa los procesos de las entradas y salidas de recursos con y sin situación de fondos, registra documentos de tipo administrativo, provee insumos a contabilidad, presupuesto, planeación estratégica, almacén, control de activos, controla impuestos, tasas y contribuciones, registre las obligaciones y egresos.
- Incluye en este modulo los procesos de gestión predial e industria y comercio.

### Gestión humana
- Registra, caracteriza y controla el recurso humano de la entidad, el proceso de nómina se debe desarrollar con insumos de la caracterización de cargos y niveles salariales iniciando en un proceso de contractual de funcionarios, solicita automáticamente el CDP (certificado de disponibilidad presupuestal) al módulo de presupuesto, este documento es necesario para la aprobación de la nómina, debe entregar insumo para pago de salarios y prestaciones sociales al módulo de tesorería.

### Control de activos
- El módulo de control de activos, que utiliza un sistema de placas para cada activo, se divide
en cuatro secciones:
1. Archivos Maestros: En esta sección se clasifica la información de los activos, incluyendo la
creación de prototipos, ubicaciones, orígenes y disposiciones de activos, así como sus
parametrizaciones.
2. Procesos: Aquí se gestionan las operaciones relacionadas con los activos, desde su creación
mediante órdenes de activación hasta la generación de actos administrativos para
construcciones en curso, maquinaria en montaje y recuperación. Se realizan también los
traslados de ubicación, cambios de responsable, disposiciones, retiros, así como el cálculo de
la depreciación inicial y mensual, incluyendo correcciones al valor del activo.
3. Herramientas: Esta sección ofrece certificados de activos, organizados por dependencias o
responsables, para facilitar el control de los activos asignados a terceros.
4. Informes: En esta sección se generan reportes de activos, fichas técnicas, conciliaciones entre
módulos, informes para la contraloría y hojas de inventario.
- Este enfoque integral permite una gestión eficiente y organizada de los activos de la entidad.

### Almacén
- El módulo está diseñado bajo la metodología tipo “Kardex” y se divide en cuatro secciones:
1. Archivos Maestros: En esta sección se definen las características del almacén, incluyendo la
creación de grupos de inventario, artículos, bodegas, unidades de medida y tipos de
movimiento.
2. Procesos: Aquí se gestionan todas las entradas y salidas del almacén. Se registran entradas
por compra, ajuste y donación, así como salidas directas y traslados al módulo de activos fijos.
También se incluyen entradas por servicio, que permiten el control de contratos que requieren
la entrega de artículos, donde el almacenista debe validar con su sello de revisión.
3. Herramientas: Esta sección proporciona recursos para facilitar el trabajo del almacenista.
Incluye la creación de actas administrativas para registrar entradas por ajuste o donación y un
inventario extracontable que permite al almacenista llevar un control de artículos que, aunque
no están físicamente en el almacén, están bajo su responsabilidad y en uso por terceros en
otras dependencias. Aquí se pueden generar actas organizadas por dependencia o por los
terceros que tengan asignados esos artículos.
4. Informes: Esta sección ofrece informes sobre la disponibilidad de inventario, movimientos por
artículo y revisiones de conciliación entre módulos.
- Este diseño integral busca optimizar la gestión del almacén y mejorar la organización de los
recursos.
MD,
            ],
            [
                'slug' => 'planeacion',
                'name' => 'Componente planeación',
                'body' => 'Planeación estratégica y articulación con la gestión de la entidad.',
                'paths' => [
                    'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                ],
                'wide' => false,
                'content' => <<<'MD'
### Planeación estratégica
- Registra información complementaria de importancia para la entidad como son los proyectos
de inversión desagregados a sector programa producto e indicador producto, insumo para la
ejecución del presupuesto, se radica y controla los PQRSD, insumo para las tareas de origen
externo, se asignan y administran las tareas internas por cascada jerárquica, se administra la
agenda personal.
MD,
            ],
            [
                'slug' => 'contractual',
                'name' => 'Componente contractual',
                'body' => 'Gestión de contratación y soporte documental para procesos institucionales.',
                'paths' => [
                    'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                ],
                'wide' => false,
                'content' => <<<'MD'
### Contratación

- Desarrolla el proceso de contratación en todas sus faces aplicando la normatividad vigente, con la utilización los insumos requeridos que el mismo sistema debe ser proveedor así: Certificado de plan de compras, certificado de disponibilidad presupuestal, certificado de banco de proyectos BPPIM, estudios previos, el resultado del proceso es la contratación del bien o servicio.
MD,
            ],
            [
                'slug' => 'apoyo_administracion',
                'name' => 'Apoyo a la administración',
                'body' => 'Herramientas MIPG, administración del software, informes y parametrización para adaptar el sistema al trabajo real de cada entidad.',
                'paths' => [
                    'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
                    'M15 12a3 3 0 11-6 0 3 3 0 016 0z',
                ],
                'wide' => true,
                'content' => <<<'MD'
### Herramientas de MIPG
- Se registra allí procesos como atención al ciudadano ventanilla única con la generación de tareas externas controladas hasta la culminación y cierre, la intranet para las comunicaciones internas, la gestión documental como formatos, políticas, procedimientos adoptados por control interno de la entidad, la administración de la documentación producida por la entidad (archivo), el registro y control de la estructura organizacional.

### Administración del software
- Registra y administra perfiles y usuarios, contraseñas, bloqueo de vigencias teniendo en cuenta que toda la información de control se maneja en una sola línea de tiempo, se configura la interfaz del software.

### Módulo de informes
- Debe generar en este módulo los diferentes reportes de información para los entes de control y otras partes interesadas, estos reportes el tipo de salida generalmente es Excel y la extensión que la plataforma a cargar la información lo requiera TXT, CSV, XML, PDF, XLSX.

### Módulo de parametrización
- Los parámetros de insumos necesarios para el proceso de información que disponga el desarrollo del aplicativo deben estar concentrados en este módulo o sección especial agrupados por procesos, para facilitar la comprensión del sistema y delegar la actividad a un especialista o consultor.
MD,
            ],
        ];

        foreach ($componentes as $order => $componente) {
            Componente::query()->updateOrCreate(
                ['slug' => $componente['slug']],
                [
                    'name' => $componente['name'],
                    'body' => $componente['body'],
                    'paths' => $componente['paths'],
                    'wide' => $componente['wide'],
                    'content' => $componente['content'],
                    'order' => $order + 1,
                ]
            );
        }
    }
}
