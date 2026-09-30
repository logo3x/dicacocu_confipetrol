@php
    $etapas = [
        [
            'sigla' => 'DI',
            'nombre' => 'Disponibilidad',
            'titulo' => 'Identificación y priorización',
            'texto' => 'Se inventarían las actividades del contrato y se valoran seis criterios de amenaza. La suma determina la prioridad, y de ella se derivan los plazos de todo el ciclo.',
            'icono' => 'fa-clipboard-list',
        ],
        [
            'sigla' => 'CA',
            'nombre' => 'Calidad',
            'titulo' => 'Estandarización y codificación',
            'texto' => 'Cada actividad prioritaria se documenta como procedimiento, se codifica y se versiona. El plazo máximo depende de la prioridad: entre uno y cuatro meses.',
            'icono' => 'fa-file-circle-check',
        ],
        [
            'sigla' => 'CO',
            'nombre' => 'Comunicación',
            'titulo' => 'Divulgación al personal',
            'texto' => 'El procedimiento se socializa con quienes ejecutan la actividad. La cobertura se mide contra el total de personas involucradas.',
            'icono' => 'fa-bullhorn',
        ],
        [
            'sigla' => 'CU',
            'nombre' => 'Cumplimiento',
            'titulo' => 'Verificación en campo',
            'texto' => 'Se acompaña la ejecución con el formato F-14. El puntaje OPT resultante determina el criterio de aprobación y las acciones a seguir.',
            'icono' => 'fa-shield-halved',
        ],
    ];
@endphp

<x-layouts.landing
    title="Sistema de Gestión Documental"
    description="Sistema de Gestión Documental y Disciplina Operativa de Confipetrol. Registro, estandarización, divulgación y verificación de los procedimientos que sostienen la operación."
>

    {{-- ══ Portada ══ --}}
    <section class="cp-portada" id="inicio">
        <div class="cp-container">
            <div class="cp-portada__inner">
                <div class="cp-portada__texto">
                    <p class="cp-eyebrow">Disciplina Operativa</p>
                    <div class="cp-accent-rule"></div>

                    <h1 class="cp-portada__titulo">
                        Los procedimientos que sostienen cada operación en campo.
                    </h1>

                    <p class="cp-portada__desc">
                        El Sistema de Gestión Documental de Confipetrol acompaña el ciclo completo de
                        la Disciplina Operativa: desde la identificación de las actividades críticas
                        hasta la verificación de su cumplimiento en campo.
                    </p>

                    <div class="cp-portada__acciones">
                        <a href="{{ route('filament.admin.auth.login') }}" class="cp-btn cp-btn--accent cp-btn--lg">
                            Acceder al sistema
                        </a>
                        <a href="#dicacocu" class="cp-btn cp-btn--ghost cp-btn--lg">
                            Conocer el ciclo DICACOCU
                        </a>
                    </div>
                </div>

                <aside class="cp-portada__resumen" aria-label="Estado del sistema">
                    <p class="cp-resumen__titulo">El sistema en cifras</p>

                    <dl class="cp-resumen__lista">
                        <div class="cp-resumen__item">
                            <dt>Procedimientos registrados</dt>
                            <dd>{{ number_format($cifras['procedimientos'], 0, ',', '.') }}</dd>
                        </div>
                        <div class="cp-resumen__item">
                            <dt>Estandarizados y codificados</dt>
                            <dd>{{ number_format($cifras['estandarizados'], 0, ',', '.') }}</dd>
                        </div>
                        <div class="cp-resumen__item">
                            <dt>Contratos activos</dt>
                            <dd>{{ number_format($cifras['contratos'], 0, ',', '.') }}</dd>
                        </div>
                        <div class="cp-resumen__item">
                            <dt>Verificaciones en campo</dt>
                            <dd>{{ number_format($cifras['verificaciones'], 0, ',', '.') }}</dd>
                        </div>
                    </dl>

                    <p class="cp-resumen__nota">Datos tomados del sistema.</p>
                </aside>
            </div>
        </div>
    </section>

    {{-- ══ Qué es DICACOCU ══ --}}
    <section class="cp-seccion" id="dicacocu">
        <div class="cp-container">
            <header class="cp-seccion__encabezado">
                <p class="cp-eyebrow">El ciclo</p>
                <div class="cp-accent-rule"></div>
                <h2 class="cp-seccion__titulo">Cuatro etapas, un mismo procedimiento</h2>
                <p class="cp-seccion__intro">
                    DICACOCU organiza la gestión de cada procedimiento en cuatro etapas consecutivas.
                    Lo que se define en la primera determina los plazos y la frecuencia de las demás,
                    de modo que la prioridad de la actividad gobierna todo su ciclo de vida.
                </p>
            </header>

            <ol class="cp-etapas">
                @foreach ($etapas as $i => $etapa)
                    <li class="cp-etapa">
                        <div class="cp-etapa__marca">
                            <span class="cp-etapa__numero">{{ $i + 1 }}</span>
                            <span class="cp-etapa__sigla">{{ $etapa['sigla'] }}</span>
                        </div>
                        <div class="cp-etapa__cuerpo">
                            <p class="cp-etapa__nombre">{{ $etapa['nombre'] }}</p>
                            <h3 class="cp-etapa__titulo">{{ $etapa['titulo'] }}</h3>
                            <p class="cp-etapa__texto">{{ $etapa['texto'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- ══ El sistema ══ --}}
    <section class="cp-seccion cp-seccion--alterna" id="sistema">
        <div class="cp-container">
            <div class="cp-sistema">
                <div class="cp-sistema__texto">
                    <p class="cp-eyebrow">El sistema</p>
                    <div class="cp-accent-rule"></div>
                    <h2 class="cp-seccion__titulo">Un registro único por contrato</h2>

                    <p class="cp-sistema__desc">
                        Cada procedimiento se registra una sola vez y recorre sus cuatro etapas
                        dentro del mismo expediente. El personal accede únicamente a los
                        procedimientos del contrato al que pertenece.
                    </p>

                    <ul class="cp-lista">
                        <li>
                            <strong>Cálculo automático de plazos.</strong>
                            La valoración de amenaza determina la prioridad, el tiempo máximo de
                            estandarización y la frecuencia de verificación.
                        </li>
                        <li>
                            <strong>Formato F-14 integrado.</strong>
                            La verificación en campo se diligencia dentro del procedimiento y su
                            puntaje OPT alimenta los indicadores del ciclo.
                        </li>
                        <li>
                            <strong>Trazabilidad completa.</strong>
                            Toda creación, edición o eliminación queda registrada con su autor,
                            la fecha y el valor anterior de cada campo.
                        </li>
                        <li>
                            <strong>Acceso con la cuenta institucional.</strong>
                            El ingreso se realiza con el correo corporativo de Confipetrol.
                        </li>
                    </ul>
                </div>

                <div class="cp-sistema__formatos">
                    <article class="cp-formato">
                        <p class="cp-formato__codigo">HSEQ-GCA1-F-17</p>
                        <h3 class="cp-formato__nombre">Matriz Integral de Disciplina Operativa</h3>
                        <p class="cp-formato__texto">
                            Consolida el inventario de actividades, su priorización y el estado de
                            cada etapa del ciclo.
                        </p>
                    </article>

                    <article class="cp-formato">
                        <p class="cp-formato__codigo">HSEQ-GCA1-F-14</p>
                        <h3 class="cp-formato__nombre">Acompañamiento y Verificación de Actividades</h3>
                        <p class="cp-formato__texto">
                            Registra la observación en campo, el puntaje OPT y la inspección
                            gerencial con las doce Reglas que Salvan Vidas.
                        </p>
                    </article>
                </div>
            </div>
        </div>
    </section>

    {{-- ══ Acceso ══ --}}
    <section class="cp-acceso" id="contacto">
        <div class="cp-container">
            <div class="cp-acceso__inner">
                <div>
                    <p class="cp-eyebrow cp-eyebrow--claro">Acceso</p>
                    <div class="cp-accent-rule"></div>
                    <h2 class="cp-acceso__titulo">Ingrese con su cuenta institucional</h2>
                    <p class="cp-acceso__desc">
                        El sistema está disponible para el personal de Confipetrol. Si requiere
                        acceso o tiene dudas sobre el uso de la plataforma, comuníquese con el
                        área de Calidad y HSEQ.
                    </p>
                </div>

                <div class="cp-acceso__accion">
                    <a href="{{ route('filament.admin.auth.login') }}" class="cp-btn cp-btn--accent cp-btn--lg">
                        Acceder al sistema
                    </a>
                    <p class="cp-acceso__nota">Correo corporativo &commat;confipetrol.com</p>
                </div>
            </div>
        </div>
    </section>

</x-layouts.landing>
