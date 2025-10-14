<?php

namespace App\Services;

use App\Models\Mitra;
use Illuminate\Support\Collection;

class SawCalculationService
{
    protected Collection $mitras;

    protected array $criteria = [
        'jarak' => 'cost',
        'honor' => 'benefit',
        'fasilitas' => 'benefit',
        'kesesuaian_jurusan' => 'benefit',
        'tingkat_kebersihan' => 'benefit',
    ];

    protected array $weights = [
        'jarak' => 0.20,
        'honor' => 0.25,
        'fasilitas' => 0.20,
        'kesesuaian_jurusan' => 0.15,
        'tingkat_kebersihan' => 0.20,
    ];

    public function __construct(Collection $mitras)
    {
        $this->mitras = $mitras;
    }

    public function calculate(): Collection
    {
        if ($this->mitras->isEmpty()) {
            return collect();
        }

        $normalizedMatrix = $this->normalizeMatrix($this->mitras);
        $rankedMitras = $this->calculateFinalScores($normalizedMatrix, $this->mitras);

        return $rankedMitras->sortByDesc('saw_score');
    }

    protected function normalizeMatrix(Collection $mitras): array
    {
        $matrix = [];
        foreach ($this->criteria as $criterion => $type) {
            $values = $mitras->pluck($criterion);

            $max = $values->max();
            $min = $values->min();

            foreach ($mitras as $index => $mitra) {
                $value = $mitra->$criterion;
                if ($type === 'benefit') {
                    $matrix[$index][$criterion] = ($max > 0) ? $value / $max : 0;
                } else { // cost
                    $matrix[$index][$criterion] = ($value > 0) ? $min / $value : 0;
                }
            }
        }
        return $matrix;
    }

    protected function calculateFinalScores(array $normalizedMatrix, Collection $mitras): Collection
    {
        foreach ($mitras as $index => $mitra) {
            $score = 0;
            foreach ($this->criteria as $criterion => $type) {
                $score += $normalizedMatrix[$index][$criterion] * $this->weights[$criterion];
            }
            $mitra->saw_score = round($score, 4);
        }

        return $mitras;
    }
}
