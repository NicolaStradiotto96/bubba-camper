<?php

namespace App\Livewire\Admin;

use App\Models\Camper;
use App\Models\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

class CamperManager extends Component
{
    use WithFileUploads;

    public ?Camper $camper = null;
    public bool $isEditMode = false;
    public $name;
    public $prices_low;
    public $prices_mid;
    public $prices_high;
    public $description;
    public $image_path;
    public $main_image;
    public $images = [];
    public $old_images = [];
    public $is_active = true;
    public bool $confirmingCamperDeletion = false;

    public $camperAttributes = [
        'main' => [
            'Caratteristiche principali' => []
        ],
        'specs' => [
            'Caratteristiche tecniche' => [],
            'Autonomia' => []
        ],
        'equipment' => [
            'Alla guida' => [],
            'Vita a bordo' => [],
            'Cucina / Dinette' => [],
            'Zona bagno' => [],
            'Esterno' => []
        ],
        'policies' => [
            'Cauzione' => []
        ]
    ];

    // CAMPER ATTRIBUTES
    private function getDefaultAttributes(): array
    {
        return [
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
    }

    // ORDER EQUIPMENT
    private function orderEquipment()
    {
        $customOrder = ['Alla guida', 'Vita a bordo', 'Cucina / Dinette', 'Zona bagno', 'Esterno'];

        $orderedEquipment = [];
        foreach ($customOrder as $key) {
            if (isset($this->camperAttributes['equipment'][$key])) {
                $orderedEquipment[$key] = $this->camperAttributes['equipment'][$key];
            }
        }

        $this->camperAttributes['equipment'] = $orderedEquipment;
    }

    // ORDER SPECS
    private function orderSpecs()
    {
        $customOrder = ['Caratteristiche tecniche', 'Autonomia'];

        $orderedSpecs = [];
        foreach ($customOrder as $key) {
            if (isset($this->camperAttributes['specs'][$key])) {
                $orderedSpecs[$key] = $this->camperAttributes['specs'][$key];
            }
        }

        $this->camperAttributes['specs'] = $orderedSpecs;
    }

    // CREATE LABEL
    private function createItem(string $label, string $value = ''): array
    {
        return ['label' => $label, 'value' => $value];
    }

    // IS ADMIN?
    public function mount(?Camper $camper = null)
    {
        if (!auth()->user()?->is_admin) {
            abort(403, 'Accesso non autorizzato.');
        }

        if ($camper && $camper->exists) {
            $this->camper = $camper;
            $this->isEditMode = true;

            $this->name = $camper->name;
            $this->description = $camper->description;
            $this->is_active = (bool) $camper->is_active;
            $this->image_path = $camper->image_path;
            $this->prices_low = $camper->prices['low'] ?? '';
            $this->prices_mid = $camper->prices['mid'] ?? '';
            $this->prices_high = $camper->prices['high'] ?? '';
            $this->old_images = $camper->images ?? [];

            if (!empty($camper->attributes)) {
                $this->camperAttributes = $camper->attributes;
            }
        } else {
            $this->camperAttributes = $this->getDefaultAttributes();
        }

        $this->orderEquipment();
        $this->orderSpecs();
    }

    // RULES
    protected function rules()
    {
        return [
            'name' => 'required|string|min:3',
            'prices_low' => 'required|numeric|min:1',
            'prices_mid' => 'required|numeric|min:1',
            'prices_high' => 'required|numeric|min:1',
            'description' => 'required|string',
            'main_image' => $this->isEditMode ? 'nullable|file|mimes:png,jpg,jpeg|max:5120' : 'required|file|mimes:png,jpg,jpeg|max:5120',
            'images.*' => 'nullable|file|mimes:png,jpg,jpeg|max:5120',
            'camperAttributes.main.*.*.label' => 'nullable|string',
            'camperAttributes.main.*.*.value' => 'nullable|string',
            'camperAttributes.specs.*.*.label' => 'nullable|string',
            'camperAttributes.specs.*.*.value' => 'nullable|string',
            'camperAttributes.equipment.*.*.label' => 'nullable|string',
            'camperAttributes.equipment.*.*.value' => 'nullable|string',
            'camperAttributes.policies.*.*.label' => 'nullable|string',
            'camperAttributes.policies.*.*.value' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }

    // ERROR MESSAGES
    protected function messages()
    {
        return [
            'name.required'        => 'Il nome del camper è obbligatorio.',
            'name.min'             => 'Il nome deve avere almeno 3 caratteri.',
            'prices_low.required'  => 'Il prezzo è obbligatorio.',
            'prices_low.numeric'   => 'Il prezzo deve essere un numero.',
            'prices_mid.required'  => 'Il prezzo è obbligatorio.',
            'prices_mid.numeric'   => 'Il prezzo deve essere un numero.',
            'prices_high.required' => 'Il prezzo è obbligatorio.',
            'prices_high.numeric'  => 'Il prezzo deve essere un numero.',
            'description.required' => 'La descrizione è obbligatoria.',
            'main_image.required'  => 'È necessario caricare un\'immagine principale.',
            'main_image.mimes'     => 'Solo i file di tipo png, jpg o jpeg sono permessi.',
            'main_image.max'       => 'L\'immagine principale non può superare i 5MB.',
            'images.*.mimes'       => 'Solo i file di tipo png, jpg o jpeg sono permessi.',
            'images.*.max'         => 'Ogni immagine della galleria non può superare i 5MB.',
            'camperAttributes.*.*.*.label' => 'Il campo etichetta non è valido.',
            'camperAttributes.*.*.*.value' => 'Il valore inserito non è valido.',
        ];
    }

    // ADD ROW
    public function addRow($category, $subCategory)
    {
        $this->camperAttributes[$category][$subCategory][] = ['label' => '', 'value' => ''];
    }

    // REMOVE ROW
    public function removeRow($category, $subCategory, $index)
    {
        unset($this->camperAttributes[$category][$subCategory][$index]);
        $this->camperAttributes[$category][$subCategory] = array_values($this->camperAttributes[$category][$subCategory]);
    }

    // DELETE FILE
    private function deleteFile(?string $path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    // REMOVE IMAGE
    public function removeNewImage($index)
    {
        unset($this->images[$index]);
        $this->images = array_values($this->images);
    }

    // REMOVE EXISTING IMAGE
    public function removeOldImage($index)
    {
        if ($this->isEditMode && isset($this->old_images[$index])) {
            unset($this->old_images[$index]);

            $this->old_images = array_values($this->old_images);
        }
    }

    // CREATE CAMPER
    public function saveCamper()
    {
        $this->validate($this->rules(), $this->messages());

        $successMessage = $this->isEditMode
            ? "Camper <b>{$this->name}</b> aggiornato con successo!"
            : "Camper <b>{$this->name}</b> creato con successo!";

        DB::transaction(function () {

            $path = $this->image_path;
            if ($this->main_image) {
                $this->deleteFile($this->image_path);
                $path = $this->main_image->store('campers', 'public');
            }

            $galleryPaths = $this->old_images;
            if ($this->isEditMode) {
                $deleted = array_diff($this->camper->images ?? [], $this->old_images);
                foreach ($deleted as $img) {
                    $this->deleteFile($img);
                }
            }
            foreach ($this->images as $image) {
                $galleryPaths[] = $image->store('campers/gallery', 'public');
            }

            $data = [
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'description' => $this->description,
                'prices' => [
                    'low' => $this->prices_low,
                    'mid' => $this->prices_mid,
                    'high' => $this->prices_high,
                ],
                'image_path' => $path,
                'images' => $galleryPaths,
                'attributes' => $this->camperAttributes,
                'is_active' => $this->is_active,
            ];

            if ($this->isEditMode) {
                $oldPrices = $this->camper->prices;
                $this->camper->update($data);

                $this->logCamper('camper_updated', "Camper aggiornato: {$this->camper->name}", $this->camper, $oldPrices);
            } else {
                $newCamper = Camper::create($data);

                $this->logCamper('camper_created', "Camper creato: {$newCamper->name}", $newCamper);
            }
        });

        return redirect()->route('index')->with('swal-success', $successMessage);
    }

    // DELETE CAMPER
    #[On('deleteCamper')]
    public function deleteCamper()
    {
        if (!$this->isEditMode || !$this->camper) return;

        DB::transaction(function () {
            $this->deleteFile($this->camper->image_path);

            if (!empty($this->camper->images)) {
                foreach ($this->camper->images as $galleryImg) {
                    $this->deleteFile($galleryImg);
                }
            }

            $this->logCamper('camper_deleted', "Camper eliminato: {$this->camper->name}", $this->camper);

            $this->camper->delete();
        });

        return redirect()->route('index')->with('swal-success', "Camper <b>{$this->camper->name}</b> eliminato con successo!");
    }

    // UPDATE ERRORS
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, $this->rules(), $this->messages());
    }

    // RENDER
    #[Layout('layouts.app')]
    #[Title('Gestione Camper')]
    public function render()
    {
        return view('livewire.admin.camper-manager');
    }

    // LOG
    private function logCamper(string $type, string $message, Camper $camper, $oldPrices = null)
    {
        Log::create([
            'type'    => $type,
            'message' => $message,
            'context' => [
                'user_id'    => auth()->id(),
                'ip_address' => request()->ip(),
                'camper_id'  => $camper->id,
                'name'       => $camper->name,
                'old_prices' => $oldPrices,
                'new_prices' => $camper->prices,
                'is_active'  => $camper->is_active,
            ],
        ]);
    }
}
