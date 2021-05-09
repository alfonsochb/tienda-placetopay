<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{

    use HasFactory;


    protected $table = 'clients';


    public $timestamps = true;


    protected $casts = [];


    protected $fillable = [
        'id',
        'names',
        'surnames',
        'email',
        'phone',
        'create_at',
        'update_at'
    ];


    public static $rules = [
        'names'       => 'required|min:3',
        'surnames'    => 'required|min:3',
        'email'       => 'required|email',
        'phone'       => 'required|min:10'
    ];


    public static $messages = [
        'names.required' => 'Se requiere el nombre.',
        'names.min'  => 'El nombre debe tener mínimo tres caracteres.',
        'surnames.required' => 'Se requiere el apellido.',
        'surnames.min'  => 'El apellido debe tener mínimo tres caracteres.',
        'email.required' => 'El email es requerido.',
        'email.email' => 'El email no tiene formato válido.',
        'phone.required'  => 'Se requiere número de celular.',
        'phone.min'  => 'El número celular debe tener diez caracteres numéricos.',
    ];


}
