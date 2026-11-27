<?php

return [
    /*
    | Nombre de jours sans aucun message après lesquels une conversation est
    | considérée comme inactive et supprimée (avec tous ses messages) par la
    | commande « chat:purge-inactive ». 0 désactive la purge.
    */
    'inactive_after_days' => (int) env('CHAT_INACTIVE_AFTER_DAYS', 365),
];
