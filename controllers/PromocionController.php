<?php
class PromocionController extends Controller {
    // No es necesario definir constructor si no se hace nada especial

    public function index() {
        $data = [
            'titulo' => 'Promociones',
            'promociones' => [
                [
                    'titulo' => '2x1 en Martes y Jueves',
                    'descripcion' => 'Disfruta de dos entradas por el precio de una todos los martes y jueves.',
                    'terminos' => 'Válido solo para funciones regulares. No acumulable con otras promociones.'
                ],
                [
                    'titulo' => 'Combo Familiar',
                    'descripcion' => '4 entradas + Palomitas + Bebidas a precio especial.',
                    'terminos' => 'Válido todos los días. Incluye 4 entradas, 2 palomitas grandes y 4 bebidas medianas.'
                ]
            ]
        ];
        
        $this->cargarVista('cliente/promociones', $data);
    }
}
