<?php
namespace App\Enums;

class TaskStatus extends Enum
{
    const Nowe = 1;
    const W_trakcie_wykonywania = 2;
    const Weryfikacja = 3;
    const Do_poprawy = 4;
    const Zakończone = 5;
}
