<?php

namespace App\Observers;

use App\Models\ProductVariant;
use Illuminate\Support\Facades\Cache;

class ProductVariantObserver
{
    public function saved(ProductVariant $product): void
    {
        $this->flush();
    }

    public function deleted(ProductVariant $product): void
    {
        $this->flush();
    }

    private function flush(): void
    {
        Cache::tags(['product_variants'])->flush();
    }
}
