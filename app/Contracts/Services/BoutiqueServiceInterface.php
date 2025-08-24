<?php

namespace App\Contracts\Services;

use App\Models\Boutique;
use App\Models\User;

interface BoutiqueServiceInterface
{
    public function createBoutique(array $data, User $user): Boutique;
    public function updateBoutique(Boutique $boutique, array $data): Boutique;
    public function deleteBoutique(Boutique $boutique): bool;
    public function approveBoutique(Boutique $boutique): bool;
    public function rejectBoutique(Boutique $boutique, string $raison = null): bool;
    public function toggleBoutiqueStatus(Boutique $boutique): bool;
    public function getApprovedBoutiques();
    public function getPendingBoutiques();
    public function canUserModifyBoutique(User $user, Boutique $boutique): bool;
    public function getBoutiqueStats(Boutique $boutique): array;
}
