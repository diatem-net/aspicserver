<?php
$data = '{
    "security" : {
        "sslEnabled" : false,
        "passwordEncryptMethod" : "md5",
        "encryptMethod" : "aes128",
        "initializationVector" : "1234567812345678",
        "ssoUID" : "SSODiatem",
        "serverPrivateKey" : "AFHEB7536276387",
        "serverUrl" : "http://172.31.6.52/aspicserver/",
        "sessionMaxTime" : "60",
        "cronKey" : "ABD5386730ABCD",
        "cronIp" : "172.16.201.172"
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
