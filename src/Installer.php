<?php

namespace Jfm\SimpleMvcRouteAttributesPackage;

use Composer\Script\Event;

class Installer
{
    public static function postInstall(Event $event)
    {
        $composer = $event->getComposer();
        $io = $event->getIO();

        $io->write("Merci d'avoir installé le package Simple MVC Route Attributes!");
        $io->write("Ce package est à but pédagogique uniquement et ne doit pas être utilisé en production.");

        $file = 'routing.php';
        $filePath = __DIR__ . '/../../../src/' . $file;
        if (is_writable($filePath)) {
            $handle = fopen($filePath, 'w');
            $content = "<?php\n\nuse Jfm\SimpleMvcRouteAttributesPackage\Routing\RouteLoader;\n\nRouteLoader::getInstance()->loadRoutes();\n";
            if (fwrite($handle, $content) === false) {
                $io->write("Impossible d'écrire dans le fichier index.php.");
                exit();
            }
            $io->write(sprintf("Le fichier %s a été modifié avec succès.", $file));
            fclose($handle);
        } else {
            $io->write(sprintf("Le fichier %s n'est pas accessible en écriture.", $file));
        }
    }
}
