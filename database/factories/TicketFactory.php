<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use App\Models\TicketStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    public function definition(): array
    {
        // Obtener los IDs de usuarios existentes
        $userIds = User::pluck('id')->toArray();
        $statusIds = TicketStatus::pluck('id')->toArray();

        $titles = [
            'No enciende mi pc',
            'No puedo entrar con mi usuario',
            'Se me olvidó mi contraseña',
            'Mi computador está muy lento',
            'No tengo internet',
            'No funciona mi correo',
            'No puedo imprimir',
            'Pantalla azul en mi equipo',
            'No funciona mi mouse y teclado',
            'Se cayó el sistema de facturación',
            'No puedo acceder a la carpeta compartida',
            'Mi sesión se cierra sola',
            'Error al abrir el programa contable',
            'No me llega el código de verificación',
            'El wifi se desconecta a cada rato',
            'No escucho audio en las reuniones',
            'Mi archivo de Excel no abre',
            'Necesito instalar un programa',
            'La pantalla parpadea',
            'No carga la página de la empresa',
        ];

        $title = $this->faker->randomElement($titles);

        return [
            'title' => $title,
            'description' => $title,
            'requester_id' => count($userIds) ? $this->faker->randomElement($userIds) : User::factory()->create()->id,
            'assigned_support_id' => count($userIds) ? $this->faker->randomElement($userIds) : null,
            'status_id' => count($statusIds) ? $this->faker->randomElement($statusIds) : TicketStatus::factory()->create()->id,
        ];
    }
}
