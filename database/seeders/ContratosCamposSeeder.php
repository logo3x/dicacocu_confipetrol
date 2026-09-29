<?php

namespace Database\Seeders;

use App\Models\Do\Campo;
use App\Models\Do\Contrato;
use Illuminate\Database\Seeder;

/**
 * Catálogo de contratos y sus campos/estaciones de Confipetrol.
 * Fuente: archivo CONTRATOS.xlsx entregado por el cliente (Zona / Cliente / Campo).
 */
class ContratosCamposSeeder extends Seeder
{
    public function run(): void
    {
        $contratos = [
            [
                'codigo' => 'CTR-001',
                'nombre' => 'DRUMMOND',
                'zona' => 'Zona 1',
                'campos' => [
                    'DIESEL',
                    'SOLDADURA',
                ],
            ],
            [
                'codigo' => 'CTR-002',
                'nombre' => 'ECOPETROL ORITO',
                'zona' => 'Zona 1',
                'campos' => [
                    'ECOPETROL ORITO',
                ],
            ],
            [
                'codigo' => 'CTR-003',
                'nombre' => 'GASES DEL CARIBE',
                'zona' => 'Zona 1',
                'campos' => [
                    'LA PAZ',
                ],
            ],
            [
                'codigo' => 'CTR-004',
                'nombre' => 'GRAN TIERRA',
                'zona' => 'Zona 1',
                'campos' => [
                    'COSTAYACO PUTUMAYO NORTE',
                    'TOROYACO -PUTUMAYO NORTE',
                    'MARY -PUTUMAYO NORTE',
                    'GUAYUYACO -PUTUMAYO NORTE',
                    'SANTANA -PUTUMAYO NORTE',
                    'JUANAMBU -PUTUMAYO NORTE',
                    'MOQUETA - PUTUMAYO NORTE',
                    'COHEMBI -PUTUMAYO SUR',
                    'QUINDE -PUTUMAYO SUR',
                    'CUMPLIDOR -PUTUMAYO SUR',
                    'QUILLACINGA - PUTUMAYO SUR',
                    'ACORDEONERO - VMM',
                    'ANGELES - VMM',
                    'MONOARAÑA - VMM',
                    'AYOMBERO -VMM',
                    'COLON -VMM',
                    'SANTALUCIA -VMM',
                ],
            ],
            [
                'codigo' => 'CTR-005',
                'nombre' => 'HOCOL',
                'zona' => 'Zona 1',
                'campos' => [
                    'CICUCO',
                    'BALLENAS',
                    'TECNICO',
                ],
            ],
            [
                'codigo' => 'CTR-006',
                'nombre' => 'PROMIGAS',
                'zona' => 'Zona 1',
                'campos' => [
                    'PAIVA',
                    'FILADELFIA',
                ],
            ],
            [
                'codigo' => 'CTR-007',
                'nombre' => 'GENERACIÓN RUBIALES',
                'zona' => 'Zona 2',
                'campos' => [
                    'GENERACIÓN RUBIALES',
                ],
            ],
            [
                'codigo' => 'CTR-008',
                'nombre' => 'GENERACION FRONTERA',
                'zona' => 'Zona 2',
                'campos' => [
                    'GENERACION FRONTERA',
                ],
            ],
            [
                'codigo' => 'CTR-009',
                'nombre' => 'GENERACIÓN ODL',
                'zona' => 'Zona 2',
                'campos' => [
                    'GENERACIÓN ODL',
                ],
            ],
            [
                'codigo' => 'CTR-010',
                'nombre' => 'ECODIESEL',
                'zona' => 'Zona 3',
                'campos' => [
                    'ECODIESEL',
                ],
            ],
            [
                'codigo' => 'CTR-011',
                'nombre' => 'RECORREDORES',
                'zona' => 'Zona 3',
                'campos' => [
                    'CP09',
                    'CASTILLA',
                    'CHICHIMENE',
                    'APIAY',
                ],
            ],
            [
                'codigo' => 'CTR-012',
                'nombre' => 'CENIT',
                'zona' => 'Zona 3',
                'campos' => [
                    'VASCONIA',
                    'SEBASTOPOL',
                    'GALAN / LIZAMA',
                    'SANTA ROSA',
                    'CHIMITA',
                    'BARRANCABERMEJA',
                ],
            ],
            [
                'codigo' => 'CTR-013',
                'nombre' => 'ECOPETROL METROLOGIA',
                'zona' => 'Zona 3',
                'campos' => [
                    'LA CIRA',
                    'CANTAGALLO',
                    'EL CENTRO',
                    'LLANITO',
                    'TECA',
                    'NARE',
                    'CASABE',
                    'PROVINCIA',
                    'CATATUMBO',
                ],
            ],
            [
                'codigo' => 'CTR-014',
                'nombre' => 'MANSAROVAR',
                'zona' => 'Zona 3',
                'campos' => [
                    'VELASQUEZ',
                ],
            ],
            [
                'codigo' => 'CTR-015',
                'nombre' => 'PROMIORIENTE',
                'zona' => 'Zona 3',
                'campos' => [
                    'PROMIORIENTE',
                ],
            ],
            [
                'codigo' => 'CTR-016',
                'nombre' => 'ECOPETROL QUIMICA',
                'zona' => 'Zona 4',
                'campos' => [
                    'QUIMICA',
                ],
            ],
            [
                'codigo' => 'CTR-017',
                'nombre' => 'EMERALD',
                'zona' => 'Zona 4',
                'campos' => [
                    'MATAMBO',
                    'CAMPO RICO',
                    'VIGIA',
                    'MARANTA',
                    'MIRTO',
                    'POTROS',
                ],
            ],
            [
                'codigo' => 'CTR-018',
                'nombre' => 'FRONTERA',
                'zona' => 'Zona 4',
                'campos' => [
                    'FRONTERA - OVH ESTATICO',
                ],
            ],
            [
                'codigo' => 'CTR-019',
                'nombre' => 'PAREX',
                'zona' => 'Zona 4',
                'campos' => [
                    'MANTENIMIENTO',
                    'CAPACHOS',
                ],
            ],
            [
                'codigo' => 'CTR-020',
                'nombre' => 'PERENCO',
                'zona' => 'Zona 4',
                'campos' => [
                    'CUERVA',
                    'CARUPANA',
                ],
            ],
        ];

        foreach ($contratos as $datos) {
            $contrato = Contrato::updateOrCreate(
                ['codigo' => $datos['codigo']],
                [
                    'nombre' => $datos['nombre'],
                    'zona' => $datos['zona'],
                    'activo' => true,
                ],
            );

            foreach ($datos['campos'] as $nombreCampo) {
                Campo::updateOrCreate(
                    ['contrato_id' => $contrato->id, 'nombre' => $nombreCampo],
                    ['activo' => true],
                );
            }
        }
    }
}
