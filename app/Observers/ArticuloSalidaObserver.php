<?php

namespace App\Observers;

use App\Models\ArticuloSalida;
use App\Models\Material;

class ArticuloSalidaObserver
{
    public function created(ArticuloSalida $articuloIn)
    {
        $this->updateMaterialQuantity($articuloIn);
    }

    private function updateMaterialQuantity($receiptItem)
    {
        $material = Material::find($receiptItem->material_id);
        if ($material) {
            if ($receiptItem->wasRecentlyCreated) {
                $material->cantidad -= $receiptItem->cantidad;
            } else {
                $original = $receiptItem->getOriginal();
                $diff = $receiptItem->cantidad - ($original['cantidad'] ?? 0);
                $material->cantidad -= $diff;
            }
            $material->save();
        }
    }
}
