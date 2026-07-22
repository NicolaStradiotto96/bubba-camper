<?php

namespace Database\Seeders;

use App\Models\Camper;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    private function createItem(string $label, ?string $value = null): array
    {
        return [
            'label' => $label,
            'value' => $value,
        ];
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory()->create([
        //     'first_name' => 'Test',
        //     'last_name' => 'User',
        //     'email' => 'test@example.com',
        //     'phone' => '+393331234567',
        //     'is_admin' => false,
        // ]);

        User::firstOrCreate(
            ['email' => config('app.admin_email')],
            [
                'first_name' => 'Stefano',
                'last_name' => 'Stradiotto',
                'phone' => '+393331234567',
                'email_verified_at' => now(),
                'is_admin' => true,
                'password' => Hash::make('password'),
            ]
        );

        if (Camper::count() === 0) {
            $attributes = [
                'main' => [
                    'Caratteristiche principali' => [
                        $this->createItem('Posti Viaggio', '6'),
                        $this->createItem('Posti Letto', '6'),
                        $this->createItem('Requisiti', 'Patente B'),
                        $this->createItem('Viaggi all\'Estero', 'Autorizzati'),
                        $this->createItem('Copertura Danni', 'Kasko'),
                        $this->createItem('Animali a Bordo', 'Ammessi'),
                        $this->createItem('Fumatori', 'Non Ammesso'),
                    ]
                ],
                'specs' => [
                    'Caratteristiche tecniche' => [
                        $this->createItem('Tipologia camper', 'Mansardato'),
                        $this->createItem('Marca veicolo', 'Citroën'),
                        $this->createItem('Modello veicolo', 'Jumper'),
                        $this->createItem('Marca allestimento', 'McLouis'),
                        $this->createItem('Modello allestimento', 'Glamys 226'),
                        $this->createItem('Paese immatricolazione', 'Italia'),
                        $this->createItem('Data immatricolazione', '04-04-2023'),
                        $this->createItem('Peso massimo a pieno carico', '3500kg'),
                        $this->createItem('Lunghezza', '6.99m'),
                        $this->createItem('Larghezza', '2.35m'),
                        $this->createItem('Altezza', '3.20m'),
                        $this->createItem('Capienza serbatoio acqua pulita', '100L'),
                        $this->createItem('Capienza serbatoio acque grigie', '100L'),
                        $this->createItem('Capienza serbatoio acque nere', '18L'),
                        $this->createItem('Carburante utilizzato', 'Diesel'),
                        $this->createItem('Capienza serbatoio carburante', '90L'),
                        $this->createItem('Consumo', 'Da 9 a 12L/100km'),
                        $this->createItem('Cambio', 'Manuale'),
                        $this->createItem('Additivo', 'AdBlue'),
                        $this->createItem('Cilindrata', '2200cc'),
                        $this->createItem('Potenza', '140CV'),
                    ],
                    'Autonomia' => [
                        $this->createItem('Alimentazione riscaldamento', 'Gas'),
                        $this->createItem('Alimentazione scaldacqua', 'Gas'),
                        $this->createItem('Alimentazione piano cottura', 'Gas'),
                        $this->createItem('Alimentazione frigo', 'Gas - 220V - 12V'),
                        $this->createItem('Tipo di prese elettriche', '220V - 12V - USB'),
                    ]
                ],
                'equipment' => [
                    'Alla guida' => [
                        $this->createItem('Servosterzo'),
                        $this->createItem('Cruise control'),
                        $this->createItem('Telecamera retromarcia'),
                        $this->createItem('Autoradio'),
                        $this->createItem('Airbag'),
                        $this->createItem('Chiusura centralizzata'),
                        $this->createItem('Pneumatici 4 stagioni'),
                        $this->createItem('Catene da neve'),
                        $this->createItem('Cunei livellatori'),
                        $this->createItem('Kit primo soccorso'),
                        $this->createItem('Climatizzatore'),
                        $this->createItem('Riscaldamento'),
                    ],
                    'Vita a bordo' => [
                        $this->createItem('Sedili girevoli'),
                        $this->createItem('Riscaldamento spazio abitativo'),
                        $this->createItem('Estintore'),
                        $this->createItem('Letto matrimoniale sopra gavone'),
                        $this->createItem('Letto matrimoniale in mansarda'),
                        $this->createItem('Letto matrimoniale in dinette'),
                    ],
                    'Cucina / Dinette' => [
                        $this->createItem('Frigorifero'),
                        $this->createItem('Congelatore'),
                        $this->createItem('Fornelli'),
                        $this->createItem('Tavolo interno'),
                        $this->createItem('Posti a tavola', '4 + 2 girevoli'),
                        $this->createItem('Lavello'),
                    ],
                    'Zona bagno' => [
                        $this->createItem('Doccia interna'),
                        $this->createItem('WC'),
                        $this->createItem('Lavabo'),
                        $this->createItem('Acqua calda'),
                    ],
                    'Esterno' => [
                        $this->createItem('Gavone'),
                        $this->createItem('Tendalino'),
                        $this->createItem('Pannello solare'),
                        $this->createItem('Portabici'),
                        $this->createItem('Posti sul portabici', '3'),
                        $this->createItem('Batteria ausiliaria'),
                    ]
                ],
                'policies' => [
                    'Cauzione' => [
                        $this->createItem('Modalità di consegna', 'Contanti - Carta'),
                        $this->createItem('Importo', '500€'),
                    ]
                ]
            ];

            Camper::create([
                'name' => 'McLouis Glamys 226',
                'slug' => Str::slug('McLouis Glamys 226'),
                'description' => 'Il 6 posti perfetto: mansardato, doppio matrimoniale e garage di grande dimensioni.',
                'prices' => [
                    'low' => 100,
                    'mid' => 120,
                    'high' => 140,
                ],
                'image_path' => 'campers/1.webp',
                'images' => [
                    'campers/1.webp',
                    'campers/2.webp',
                    'campers/3.webp',
                ],
                'attributes' => $attributes,
                'is_active' => true,
            ]);
        }
    }
}
