<?php
namespace App\Enums;

class WarehouseDocumentCategory extends Enum
{
    const Pobranie = 1;
    const Zwrot_do_magazynu = 2;
    const Przyjęcie = 3;
    const Zwrot = 4;
    const Likwidacja = 5;
}
