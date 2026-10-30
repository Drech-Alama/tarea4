<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateInventoryUser extends Command
{
    protected $signature = 'app:user-create';

    protected $description = 'Crea una cuenta para ingresar al panel de inventario';

    public function handle(): int
    {
        $name = $this->ask('Nombre del usuario');
        $email = $this->ask('Correo electrónico');
        $password = $this->secret('Contraseña (mínimo 8 caracteres)');
        $passwordConfirmation = $this->secret('Confirma la contraseña');

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $passwordConfirmation,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'email.unique' => 'Ya existe una cuenta con ese correo.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        User::create($validator->validated());

        $this->info('Cuenta creada. Ya puedes iniciar sesión.');

        return self::SUCCESS;
    }
}
