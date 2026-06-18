<?php

namespace App\Livewire\Admin;

use App\Models\Camper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class CamperManager extends Component
{
    use WithFileUploads;

    public ?Camper $camper = null;
    public bool $isEditMode = false;

    public $name;

    public $prices = [
        'low' => '',
        'mid' => '',
        'high' => ''
    ];

    public $description;
    public $image_path;
    public $main_image;
    public $images = [];
    public $old_images = [];

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

    public $is_active = true;

    public bool $confirmingCamperDeletion = false;

    private function createItem(string $label, string $value = ''): array
    {
        return ['label' => $label, 'value' => $value];
    }

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

    public function mount(?Camper $camper = null)
    {
        if ($camper && $camper->exists) {
            $this->camper = $camper;
            $this->isEditMode = true;

            $this->name = $camper->name;
            $this->description = $camper->description;
            $this->is_active = (bool) $camper->is_active;
            $this->image_path = $camper->image_path;

            $this->prices = $camper->prices ?? $this->prices;
            $this->old_images = $camper->images ?? [];

            if (!empty($camper->attributes)) {
                $this->camperAttributes = $camper->attributes;

                $this->orderEquipment();
                $this->orderSpecs();
                return;
            }
        }

        $this->camperAttributes['main']['Caratteristiche principali'] = [
            $this->createItem('Posti Viaggio', '6'),
            $this->createItem('Posti Letto', '6'),
            $this->createItem('Requisiti', 'Patente B'),
            $this->createItem('Viaggi all\'Estero', 'Autorizzati'),
            $this->createItem('Copertura Danni', 'Kasko'),
            $this->createItem('Animali a Bordo', 'Ammessi'),
            $this->createItem('Fumatori', 'Non Ammesso'),
        ];

        $this->camperAttributes['specs']['Caratteristiche tecniche'] = [
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
        ];

        $this->camperAttributes['specs']['Autonomia'] = [
            $this->createItem('Alimentazione riscaldamento', 'Gas'),
            $this->createItem('Alimentazione scaldacqua', 'Gas'),
            $this->createItem('Alimentazione piano cottura', 'Gas'),
            $this->createItem('Alimentazione frigo', 'Gas - 220V - 12V'),
            $this->createItem('Tipo di prese elettriche', '220V - 12V - USB'),
        ];

        $this->camperAttributes['equipment']['Alla guida'] = [
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
        ];

        $this->camperAttributes['equipment']['Vita a bordo'] = [
            $this->createItem('Sedili girevoli'),
            $this->createItem('Riscaldamento spazio abitativo'),
            $this->createItem('Estintore'),
            $this->createItem('Letto matrimoniale sopra gavone'),
            $this->createItem('Letto matrimoniale in mansarda'),
            $this->createItem('Letto matrimoniale in dinette'),
        ];

        $this->camperAttributes['equipment']['Cucina / Dinette'] = [
            $this->createItem('Frigorifero'),
            $this->createItem('Congelatore'),
            $this->createItem('Fornelli'),
            $this->createItem('Tavolo interno'),
            $this->createItem('Posti a tavola', '4 + 2 girevoli'),
            $this->createItem('Lavello'),
        ];

        $this->camperAttributes['equipment']['Zona bagno'] = [
            $this->createItem('Doccia interna'),
            $this->createItem('WC'),
            $this->createItem('Lavabo'),
            $this->createItem('Acqua calda'),
        ];

        $this->camperAttributes['equipment']['Esterno'] = [
            $this->createItem('Gavone'),
            $this->createItem('Tendalino'),
            $this->createItem('Pannello solare'),
            $this->createItem('Portabici'),
            $this->createItem('Posti sul portabici', '3'),
            $this->createItem('Batteria ausiliaria'),
        ];

        $this->camperAttributes['policies']['Cauzione'] = [
            $this->createItem('Modalità di consegna', 'Contanti - Carta'),
            $this->createItem('Importo', '500€'),
        ];

        $this->orderEquipment();
        $this->orderSpecs();
    }

    public function addRow($category, $subCategory)
    {
        $this->camperAttributes[$category][$subCategory][] = ['label' => '', 'value' => ''];
    }

    public function removeRow($category, $subCategory, $index)
    {
        unset($this->camperAttributes[$category][$subCategory][$index]);
        $this->camperAttributes[$category][$subCategory] = array_values($this->camperAttributes[$category][$subCategory]);
    }

    public function removeNewImage($index)
    {
        unset($this->images[$index]);
        $this->images = array_values($this->images);
    }

    public function removeOldImage($index)
    {
        if ($this->isEditMode && isset($this->old_images[$index])) {
            unset($this->old_images[$index]);

            $this->old_images = array_values($this->old_images);

            session()->flash('info', 'Foto rimossa dall\'anteprima. Ricorda di salvare le modifiche.');
        }
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|min:3',
            'prices.low' => 'required|numeric|min:1',
            'prices.mid' => 'required|numeric|min:1',
            'prices.high' => 'required|numeric|min:1',
            'description' => 'required|string',
            'main_image' => $this->isEditMode ? 'nullable|image|max:5120' : 'required|image|max:5120',
            'images.*' => 'nullable|image|max:5120',
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

    public function saveCamper()
    {
        $this->validate();

        DB::transaction(function () {

            $path = $this->image_path;
            if ($this->main_image) {
                if ($this->isEditMode && $this->image_path) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($this->image_path);
                }
                $path = $this->main_image->store('campers', 'public');
            }

            $galleryPaths = $this->old_images;

            if ($this->isEditMode) {
                $originalImages = $this->camper->images ?? [];
                $deletedImages = array_diff($originalImages, $this->old_images);

                foreach ($deletedImages as $deletedImg) {
                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($deletedImg)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($deletedImg);
                    }
                }
            }

            if ($this->images) {
                foreach ($this->images as $image) {
                    $galleryPaths[] = $image->store('campers/gallery', 'public');
                }
            }

            if ($this->isEditMode) {
                $this->camper->update([
                    'name' => $this->name,
                    'slug' => Str::slug($this->name),
                    'description' => $this->description,
                    'prices' => $this->prices,
                    'image_path' => $path,
                    'images' => $galleryPaths,
                    'attributes' => $this->camperAttributes,
                    'is_active' => $this->is_active,
                ]);
                session()->flash('success', 'Camper modificato con successo!');
            } else {
                Camper::create([
                    'name' => $this->name,
                    'slug' => Str::slug($this->name),
                    'description' => $this->description,
                    'prices' => $this->prices,
                    'image_path' => $path,
                    'images' => $galleryPaths,
                    'attributes' => $this->camperAttributes,
                    'is_active' => $this->is_active,
                ]);
                session()->flash('success', 'Camper creato con successo!');
            }
        });

        return redirect()->route('index');
    }

    public function deleteCamper()
    {
        if (!$this->isEditMode || !$this->camper) {
            return;
        }

        DB::transaction(function () {
            if ($this->camper->image_path && Storage::disk('public')->exists($this->camper->image_path)) {
                Storage::disk('public')->delete($this->camper->image_path);
            }

            if (!empty($this->camper->images)) {
                foreach ($this->camper->images as $galleryImg) {
                    if (Storage::disk('public')->exists($galleryImg)) {
                        Storage::disk('public')->delete($galleryImg);
                    }
                }
            }

            $this->camper->delete();

            session()->flash('success', 'Camper eliminato definitivamente dal sistema.');
        });

        return redirect()->route('index');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.admin.camper-manager');
    }
}
