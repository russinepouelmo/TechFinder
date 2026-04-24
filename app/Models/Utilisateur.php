<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Utilisateur extends Model
{
    use HasFactory;
    protected $table = 'utilisateurs';
    protected $primaryKey = 'code_user';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;

    protected $fillable = [
        'code_user',
        'nom_user',
        'prenom_user',
        'login_user',
        'password_user',
        'tel_user',
        'sexe_user',
        'role_user',
        'etat_user',
    ];

    public static function generateUniqueMatricule(): string
    {
        $lastCode = self::where('code_user', 'like', 'MAT%')
            ->orderByDesc('code_user')
            ->value('code_user');

        $nextNumber = 1;

        if ($lastCode && preg_match('/^MAT(\d+)$/', $lastCode, $matches)) {
            $nextNumber = ((int) $matches[1]) + 1;
        }

        do {
            $candidate = 'MAT' . str_pad((string) $nextNumber, 5, '0', STR_PAD_LEFT);
            $nextNumber++;
        } while (self::where('code_user', $candidate)->exists());

        return $candidate;
    }
    function interventions()
    {
        return $this->hasMany(Intervention::class, 'code_user', 'code_user');
    }


    function competences()
    {

        return $this->belongsToMany(Competence::class, 'user_competences', 'code_user', 'code_comp');
    }

    public function userCompetences()
    {
        return $this->hasMany(User_Competence::class, 'code_user', 'code_user');
    }
}
