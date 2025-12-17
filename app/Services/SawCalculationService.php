<?php

namespace App\Services;

use App\Models\Mitra;
use Illuminate\Support\Collection;

class SawCalculationService
{
    protected Collection $mitras;

    /**
     * Kriteria penilaian dengan tipe:
     * - cost: semakin kecil semakin baik (jarak)
     * - benefit: semakin besar semakin baik (honor, fasilitas, kesesuaian, kebersihan)
     */
    protected array $criteria = [
        'jarak' => 'cost',
        'honor' => 'benefit',
        'fasilitas' => 'benefit',
        'kesesuaian_jurusan' => 'benefit',
        'tingkat_kebersihan' => 'benefit',
    ];

    /**
     * Bobot kriteria SAW (Simple Additive Weighting)
     * Total bobot = 1.00 (100%)
     * - Jarak: 20%
     * - Honor: 25%
     * - Fasilitas: 20%
     * - Kesesuaian Jurusan: 15%
     * - Tingkat Kebersihan: 20%
     */
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

    /**
     * Menghitung ranking mitra menggunakan metode SAW
     * @return Collection Mitra yang sudah diurutkan berdasarkan saw_score (tertinggi ke terendah)
     */
    public function calculate(): Collection
    {
        if ($this->mitras->isEmpty()) {
            return collect();
        }

        $normalizedMatrix = $this->normalizeMatrix($this->mitras);
        $rankedMitras = $this->calculateFinalScores($normalizedMatrix, $this->mitras);

        return $rankedMitras->sortByDesc('saw_score');
    }

    /**
     * Normalisasi matriks keputusan
     * - Benefit (semakin besar semakin baik): Rij = Xij / Max(Xij)
     * - Cost (semakin kecil semakin baik): Rij = Min(Xij) / Xij
     */
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
                    // Normalisasi benefit: nilai / nilai maksimum
                    $matrix[$index][$criterion] = ($max > 0) ? $value / $max : 0;
                } else { // cost
                    // Normalisasi cost: nilai minimum / nilai
                    $matrix[$index][$criterion] = ($value > 0) ? $min / $value : 0;
                }
            }
        }
        return $matrix;
    }

    /**
     * Menghitung skor akhir dengan mengalikan nilai ternormalisasi dengan bobot
     * Formula: Vi = Σ(Wj * Rij)
     */
    protected function calculateFinalScores(array $normalizedMatrix, Collection $mitras): Collection
    {
        foreach ($mitras as $index => $mitra) {
            $score = 0;
            foreach ($this->criteria as $criterion => $type) {
                $normalizedValue = $normalizedMatrix[$index][$criterion];
                $score += $normalizedValue * $this->weights[$criterion];

                // Menyimpan nilai normalisasi ke objek mitra
                $mitra->{'normalized_' . $criterion} = round($normalizedValue, 4);
            }
            $mitra->saw_score = round($score, 4);
        }

        return $mitras;
    }
}
