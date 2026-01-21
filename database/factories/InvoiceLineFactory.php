<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\InvoiceTax;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvoiceLine>
 */
class InvoiceLineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(1, 10);
        $priceExc = $this->faker->randomFloat(2, 10, 500);
        $tax = InvoiceTax::inRandomOrder()->first() ?? InvoiceTax::factory()->create();
        $taxRate = $tax->percentage / 100;

        $priceTax = round($priceExc * $taxRate, 2);
        $price = round($priceExc + $priceTax, 2);

        $totalExc = round($priceExc * $quantity, 2);
        $totalTax = round($totalExc * $taxRate, 2);
        $total = round($totalExc + $totalTax, 2);

        return [
            'invoice_id' => Invoice::factory(),
            'name' => $this->faker->words(3, true),
            'quantity' => $quantity,
            'price' => $price,
            'price_tax' => $priceTax,
            'price_exc' => $priceExc,
            'total' => $total,
            'total_exc' => $totalExc,
            'total_tax' => $totalTax,
            'tax_id' => $tax->id,
        ];
    }
}
