<?php
$data = '{
    "security" : {
        "sslEnabled" : false,
        "passwordEncryptMethod" : "md5",
        "encryptMethod" : "aes128",
        "initializationVector" : "<Vecteur initialisation AES128>",
        "ssoUID" : "SSODiatem",
        "serverPrivateKey" : "<Clé privée du serveur>",
        "serverUrl" : "<Url du serveur>",
        "sessionMaxTime" : "60",
        "cronKey" : "<Clé pour les taches CRON>",
        "cronIp" : "<IP du serveur accepté pour les taches CRON>"
    },
    "logs" : {
        "logfile" : "logs.txt",
        "enabled" : true,
        "log_phperrors" : true,
        "log_cnxfail" : true,
        "log_securityalert" : true,
        "log_cnxsuccess" : true,
        "log_ticketcheck" : true
    },
    "render" : {
        "template" : ""
    },
    "ticketManagement" : {
        "method" : "file",
        "parameters" : {
           "storage" : "data/"
        }
    },
    "authentification" : {
        "method" : "database",
        "parameters" : {
        "query" : "SELECT tt_password FROM tb_utilisateur WHERE tt_identifiant=?",
        "passwordFieldName" : "tt_password"
        }
    },
    "appzCredentialCheckQuerying" : {
        "method" : "database",
        "enabled" : "true",
        "parameters" : {
        "query" : "SELECT gda.tt_localgroupe AS groupname, a.tt_code AS appzid  FROM tb_utilisateur AS u JOIN tb_groupedroit AS gd ON gd.pk_groupedroit = u.fk_groupedroit JOIN tb_groupedroit_appz AS gda ON gda.fk_groupedroit = gd.pk_groupedroit JOIN tb_appz AS a ON a.pk_appz = gda.fk_appz WHERE u.tt_identifiant=?",
        "serviceIdField" : "appzid",
        "groupNameField" : "groupname"
        }
    },
    "ipBlackList" : {
        "method" : "file",
        "enabled" : "true",
        "maxAttempts" : "10",
        "blackListTime" : "30",
        "parameters" : {
            "storage" : "data/"
        }
    },
    "accessLog" : {
        "method" : "database",
        "enabled" : "true",
        "parameters" : {
            "query" : "INSERT INTO tb_connexion (fk_utilisateur, fk_appz) VALUES ((SELECT pk_utilisateur FROM tb_utilisateur WHERE tt_identifiant=:userId), (SELECT pk_appz FROM tb_appz WHERE tt_code=:serviceId));"
        }
    },
    "userDataQuerying" : {
        "method" : "database",
        "enabled" : "true",
        "parameters" : {
            "query" : "SELECT tt_nom, tt_prenom FROM tb_utilisateur WHERE tt_identifiant=?",
              "fields" : {
                  "nom" : "tt_nom",
                  "prenom" : "tt_prenom"
              }
        }
    },
    "extraArguments" : {
	"enabled" : "true"
    },
    "database" : {
        "dbType" : "postgresql",
        "dbHost" : "127.0.0.1",
        "dbName" : "hyproweb",
        "dbUser" : "postgres",
        "dbPort" : "5432",
        "dbPassword" : "devadmin"
    },
    "librariesLocation" : {
       "jin" : "../framework-jin/jin/"
    }
}';
