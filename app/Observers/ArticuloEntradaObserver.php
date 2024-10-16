<?php

namespace App\Observers;

use App\Models\ArticuloEntrada;
use App\Models\Material;

class ArticuloEntradaObserver
{
    //
    public function created(ArticuloEntrada $articuloIn)
    {
        $this->updateMaterialQuantity($articuloIn);
    }
    
    private function updateMaterialQuantity( $receiptItem)
    {
        $material = Material::find($receiptItem->material_id);
        if ($material) {
            if ($receiptItem->wasRecentlyCreated) {
                $material->cantidad += $receiptItem->cantidad;
            } else {
                $original = $receiptItem->getOriginal();
                $diff = $receiptItem->cantidad - ($original['cantidad'] ?? 0);
                $material->cantidad += $diff;
            }
            $material->save();
        }
    }
}
