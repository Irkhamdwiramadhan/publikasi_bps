<?php

namespace App\Policies;

use App\Models\SubmissionPublication;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SubmissionPublicationPolicy
{
    /**
     * Tentukan apakah user bisa melihat semua data.
     * (Kita bisa gunakan ini nanti untuk menggantikan logika di controller)
     */
    public function viewAny(User $user): bool
    {
        // Izinkan jika user adalah Admin atau Pemeriksa
        return $user->hasAnyRole(['Admin', 'Pemeriksa', 'Penyusun', 'Pimpinan']);
    }

    /**
     * Tentukan apakah user bisa melihat detail data.
     */
    public function view(User $user, SubmissionPublication $submissionPublication): bool
    {
        // Izinkan Admin/Pemeriksa, atau jika user adalah pemilik
        if ($user->hasAnyRole(['Admin', 'Pemeriksa'])) {
            return true;
        }
        return $user->id === $submissionPublication->user_id;
    }

    /**
     * Tentukan apakah user bisa membuat data baru.
     */
    public function create(User $user): bool
    {
        // Hanya Penyusun yang bisa membuat
        return $user->hasRole('Penyusun');
    }

    /**
     * [PENTING] Tentukan apakah user bisa mengedit (update) data.
     * Ini dipanggil oleh $this->authorize('update', ...)
     */
    public function update(User $user, SubmissionPublication $submissionPublication): bool
    {
        // 1. Izinkan jika user adalah Admin atau Pemeriksa
        if ($user->hasAnyRole(['Admin', 'Pemeriksa'])) {
            return true;
        }

        // 2. Izinkan jika user adalah Penyusun yang membuat pengajuan ini
        return $user->id === $submissionPublication->user_id;
    }

    /**
     * [PENTING] Tentukan apakah user bisa mengubah status.
     * Ini dipanggil oleh $this->authorize('updateStatus', ...)
     */
    public function updateStatus(User $user, SubmissionPublication $submissionPublication): bool
    {
        // HANYA Admin atau Pemeriksa yang bisa mengubah status
        return $user->hasAnyRole(['Admin', 'Pemeriksa']);
    }

    /**
     * Tentukan apakah user bisa menghapus data.
     */
    public function delete(User $user, SubmissionPublication $submissionPublication): bool
    {
        // Hanya Admin yang bisa menghapus
        return $user->hasRole('Admin');
    }
}
