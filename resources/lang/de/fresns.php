<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Fresns Console Language Lines
    |--------------------------------------------------------------------------
    */

    //Login page
    'language' => 'Sprache',
    'login' => 'Anmeldung',
    'account' => 'Konto-Nummer',
    'password' => 'Passwort',
    'enter' => 'Eingeben',
    //Menu
    'logout' => 'Abmelden',
    'menuDashboard' => 'Dashboard',
    'menuSettings' => 'Einstellungen',
    'menuKeys' => 'Schlüssel',
    'menuAdmins' => 'Admins',
    'menuWebsites' => 'Websites',
    'menuApps' => 'Apps',
    'menuPlugins' => 'Plugins',
    'menuAppStore' => 'App Store',
    //Dashboard
    'welcome' => 'Willkommen bei Fresns',
    'currentVersion' => 'Die aktuell eingesetzte Version ist',
    'overview' => 'Übersicht',
    'userCounts' => 'Konto zählen',
    'memberCounts' => 'Benutzer zählen',
    'groupCounts' => 'Gruppen zählen',
    'hashtagCounts' => 'Hashtag zählen',
    'postCounts' => 'Beiträge zählen',
    'commentCounts' => 'Kommentare zählen',
    'extensions' => 'Erweiterungen',
    'keys' => 'Tasten',
    'controlPanel' => 'Bedienfeld',
    'engines' => 'Engines',
    'themes' => 'Themes',
    'apps' => 'Apps',
    'plugins' => 'Plugins',
    'support' => 'Unterstützung',
    'fresnsSite' => 'Offizielle Website',
    'fresnsTeam' => 'Team',
    'fresnsPartners' => 'Partner',
    'fresnsJoin' => 'Mitmachen',
    'fresnsAppStore' => 'App Store',
    'fresnsCommunity' => 'Gemeinschaft',
    'news' => 'Fresns Veranstaltungen und Nachrichten',
    'installs' => 'Installationen',
    'installIntro' => 'Wenn die Anwendungsdateien im angegebenen Verzeichnis abgelegt wurden, wählen Sie Lokale Installation. Wenn Sie im Fresns-App-Store gekauft und einen Installationscode erhalten haben, verwenden Sie die Ferninstallation.',
    'localInstall' => 'Lokale Installation',
    'localInstallBtn' => 'Bestätigen Sie',
    'localInstallInfo' => 'Anwendungsdateien bereits im angegebenen Verzeichnis abgelegt, geben Sie den Namen des zu installierenden Ordners ein',
    'codeInstall' => 'Code installieren',
    'codeInstallBtn' => 'Absenden',
    'codeInstallInfo' => 'App im Fresns App Store gekauft, Installationscode eingeben, um zu installieren',
    'folderName' => 'Name des Ordners',
    'fresnsCode' => 'Fresns Code',
    'updates' => 'Aktualisieren',
    'updatesNull' => 'Keine Updates verfügbar',
    'updateBtn' => 'Aktualisieren',
    //Settings
    'consoleTitle' => 'Konsolen-Einstellungen',
    'consoleIntro' => 'Fresns Backend-Konsolen-Systemeinstellung',
    'backendDomain' => 'Backend-Domäne',
    'backendDomainInfo' => 'Standard-Zugangsadresse für Hauptanwendungs-API und Plugins, ohne / am Ende',
    'backendPath' => 'Backend-Pfad',
    'backendPathInfo' => 'Einstellen, dass die Anmeldung an der Konsole nur über das angegebene sichere Portal erfolgt',
    'consoleUrlName' => 'Konsolen-URL',
    'copyConsoleUrl' => 'Kopieren',
    'copyConsoleUrlSuccess' => 'Reproduktionserfolg',
    'copyConsoleUrlWarning' => 'Fehler beim Kopieren',
    'siteDomain' => 'Site-Domäne',
    'siteDomainInfo' => 'Hauptstandort-Laufadresse (integrierter oder eigenständiger Einsatz) ohne / am Ende',
    'consoleSettingBtn' => 'Einstellungen speichern',
    'systemAdminTitle' => 'Systemverwalter',
    'systemAdminIntro' => 'Administrator mit Zugriff auf die Konsole',
    'systemAdminUserId' => 'Benutzer-ID',
    'systemAdminAccount' => 'Konto',
    'systemAdminOptions' => 'Optionen',
    'deleteSystemAdmin' => 'Löschen',
    'addSystemAdmin' => 'Administrator hinzufügen',
    'addSystemAdminTitle' => 'Neuer Systemadministrator',
    'addSystemAdminAccount' => 'Konto',
    'addSystemAdminAccountDesc' => 'E-Mail oder Telefonnummer',
    'addSystemAdminAccountInfo' => 'Die Mobiltelefonnummer muss eine vollständige Nummer mit internationaler Vorwahl sein',
    'addSystemAdminBtn' => 'Suchen und hinzufügen',
    //Keys
    'keysTitle' => 'API-Schlüssel',
    'keysIntro' => 'Wichtige Anmeldeinformationen sind wichtig und sollten nicht einfach an andere weitergegeben werden.',
    'keysNull' => 'Kein Schlüssel',
    'keysTablePlatform' => 'Plattform',
    'keysTableName' => 'Name',
    'keysTableAppId' => 'App ID',
    'keysTableAppSecret' => 'App Secret',
    'keysTableType' => 'Typ',
    'keysTableEnableStatus' => 'Aktivieren Status',
    'keysTableOptions' => 'Optionen',
    'keysTableOptionEdit' => 'Bearbeiten',
    'keysTableOptionReset' => 'Taste zurücksetzen',
    'keysTableOptionDelete' => 'Löschen',
    'addKey' => 'Schlüssel hinzufügen',
    'addKeyTitle' => 'Neuen Schlüssel erstellen',
    'addKeyBtn' => 'Zuweisen zum Erstellen',
    'editKeyTitle' => 'Bearbeiten Taste',
    'editKeyBtn' => 'Bearbeitungen einreichen',
    'keyFormPlatform' => 'Plattform',
    'keyFormPlatformChooseOption' => 'Wählen Sie die wichtigste Anwendungsplattform',
    'keyFormName' => 'Name',
    'keyFormType' => 'Typ',
    'keyFormTypePlugin' => 'Zugeordnetes Plugin',
    'keyFormTypePluginInfo' => 'Der Schlüssel erlaubt es nicht, die Haupt-API der Anwendung anzufordern',
    'keyFormTypePluginChooseOption' => 'Wählen Sie, für welches Plugin der Schlüssel verwendet werden soll',
    'keyFormStatus' => '状态',
    'keyTypeFresns' => 'Fresns API',
    'keyTypePlugin' => 'Plugin API',
    'keyStatusActivate' => '启用',
    'keyStatusDeactivate' => '停用',
    //Admins
    'adminsTitle' => 'Kontrollzentrum',
    'adminsIntro' => 'Installieren Sie optional ein anderes Bedienfeld, um verschiedene Funktionseinstellungen und Verwaltungsmethoden zu erleben.',
    'adminsNull' => 'Es ist noch kein Bedienfeld installiert',
    //Website
    'enginesTitle' => 'Engines',
    'enginesIntro' => 'Wählen Sie eine andere Engine für mehr personalisierte Funktionen und Dienste',
    'enginesNull' => 'Noch keine Website-Engine installiert',
    'enginesTableName' => 'Engine',
    'enginesTableNameInfo' => 'Wenn Sie eine eigenständige Website oder eine mobile Anwendung ohne Website bereitstellen möchten. Deaktivieren" oder "deinstallieren" Sie einfach die Website-Engine, so dass Fresns nur noch ein Backend-System ist, auf dem APIs und Plugins laufen.',
    'enginesTableAuthor' => 'Autor',
    'enginesTableTheme' => 'Thema',
    'enginesTableThemePcNull' => 'Nicht gesetzt',
    'enginesTableThemeMobileNull' => 'Nicht gesetzt',
    'enginesTableOptions' => 'Optionen',
    'enginesTableOptionsInfo' => 'Es können mehrere Engines aktiviert werden, solange sie nicht mit den Pfaden der anderen in Konflikt geraten, fragen Sie den Entwickler der Engine nach Details.',
    'enginesTableOptionsTheme' => 'Zugewiesenes Theme',
    'engineThemeTitle' => 'Zugewiesene Theme-Vorlage',
    'engineThemeNoOption' => 'Kein Thema',
    'engineThemePc' => 'Computer-Themen',
    'engineThemeMobile' => 'Mobile Themen',
    'engineThemeBtn' => 'Speichern',
    'themesTitle' => 'Thema',
    'themesIntro' => 'Wählen Sie verschiedene Themen für einen persönlicheren Stil und mehr Interaktion.',
    'themesNull' => 'Keine Themenvorlagen verfügbar',
    //Apps
    'appsTitle' => 'Apps',
    'appsIntro' => 'Installieren Sie optional verschiedene Apps, um unterschiedliche Betriebsszenarien und Anwendungsmodi zu erstellen.',
    'appsNull' => 'Noch keine mobile App installiert',
    //Plugins
    'pluginsTitle' => 'Plugins',
    'pluginsIntro' => 'Flexible Funktionen, leistungsstarke Erweiterungen, Sie können spielen, was Sie wollen.',
    'pluginsNull' => 'Kein Plug-In installiert',
    'pluginsTabAll' => 'Alle',
    'pluginsTabActive' => 'Aktiv',
    'pluginsTabInactive' => 'Inaktiv',
    'pluginsTableName' => 'Name',
    'pluginsTableDesc' => 'Beschreibung',
    'pluginsTableAuthor' => 'Autor',
    'pluginsTableOptions' => 'Optionen',
    //Controls
    'author' => 'Autor',
    'activate' => 'Aktivieren',
    'activateInfo' => 'Klicken Sie zum Aktivieren',
    'uninstall' => 'Deinstallieren',
    'uninstallInfo' => 'Zum Deinstallieren anklicken',
    'deactivate' => 'Deaktivieren',
    'deactivateInfo' => 'Zum Deaktivieren anklicken',
    'setting' => 'Einstellung',
    'settingInfo' => 'Gehen Sie zur Einstellungsseite',
    'newVersion' => 'Neue',
    'newVersionInfo' => 'Klicken Sie zum Aktualisieren in das Dashboard',
    'cancel' => 'Abbrechen',
    'confirmDelete' => 'Bestätigen der Löschung',
    'confirmUninstall' => 'Bestätigen Sie die Deinstallation',
    'inputNull' => 'Kann nicht leer sein',
    'inputError' => 'Falsch',
    //Local Install Step
    'localInstallStep1' => 'Ordner finden',
    'localInstallStep2' => 'Überprüfung der Initialisierung',
    'localInstallStep3' => 'Installation der Erweiterung',
    'localInstallStep4' => 'Cache leeren',
    'localInstallStep5' => 'Erledigt',
    //Code Install Step
    'codeInstallStep1' => 'Überprüfung der Initialisierung',
    'codeInstallStep2' => 'Erweiterungspaket herunterladen',
    'codeInstallStep3' => 'Das Erweiterungspaket entpacken',
    'codeInstallStep4' => 'Erweiterung installieren',
    'codeInstallStep5' => 'Cache leeren',
    'codeInstallStep6' => 'Erledigt',
    //Update Step
    'updateStep1' => 'Überprüfung der Initialisierung',
    'updateStep2' => 'Erweiterungspaket herunterladen',
    'updateStep3' => 'Das Erweiterungspaket entpacken',
    'updateStep4' => 'Erweiterung aktualisieren',
    'updateStep5' => 'Cache leeren',
    'updateStep6' => 'Erledigt',
    //Uninstall Step
    'uninstallOption' => 'Gleichzeitige Löschung von Daten aus diesem Plugin',
    'uninstallStep1' => 'Überprüfung der Initialisierung',
    'uninstallStep2' => 'Datenverarbeitung',
    'uninstallStep3' => 'Löschen von Dateien',
    'uninstallStep4' => 'Cache leeren',
    'uninstallStep5' => 'Erledigt',
];
