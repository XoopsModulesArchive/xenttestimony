#xEntTestimony
# Généré le : Mardi 28 Septembre 2004 à 10:56
# Version du serveur: 4.0.15
# Version de PHP: 4.3.3

#
# Structure de la table `xent_tt_quote`
#

CREATE TABLE `xent_tt_quote` (
    `id`               INT(11) NOT NULL AUTO_INCREMENT,
    `id_user`          INT(5)  NOT NULL DEFAULT '0',
    `quote_experience` TEXT    NOT NULL,
    `quote_quotetitle` TEXT    NOT NULL,
    `citation`         TEXT    NOT NULL,
    `status`           INT(5)  NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `id` (`id`)
)
    ENGINE = ISAM
    AUTO_INCREMENT = 1;

#
# Contenu de la table `xent_cr_quote`
#

