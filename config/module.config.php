<?php
/**
 * Configuration du module : rien à configurer ici
 * 
 * Copiez le fichier config/manage-layout.global.dist.php dans le dossier config/autoload de votre application
 * et adaptez le à votre besoin
 *
 * @project dafap/ManageLayout
 * @package config
 * @filesource module.config.php
 * @encodage UTF-8
 * @author DAFAP Informatique - Alain Pomirol (dafap@free.fr)
 * @date 24 avr. 2014
 * @version 2014-1
 */
return array(
    /**
     * Il faut garder cette structure car il n'y a pas de vérification d'existence de ces clés
     */
    'layout_manager' => array(
        'layout_map' => array(),
        'parameter' => array()
    ),
    'service_manager' => array(
        'invokables' => array(
            'DafapLayout\LayoutListener' => 'DafapLayout\Listener\LayoutListener',
            'DafapLayout\LayoutErrorListener' => 'DafapLayout\Listener\LayoutErrorListener'
        ),
        'factories' => array(
            'DafapLayout\Config' => 'DafapLayout\Service\ConfigServiceFactory'
        )
    ),
    'view_helpers' => array(
        'invokables' => array(
            'getParameter' => 'DafapLayout\View\Helper\LayoutParameter',
            'getHeadLinks' => 'DafapLayout\View\Helper\HeadLinks',
            'getHeadScripts' => 'DafapLayout\View\Helper\HeadScripts'
        )
    )
);