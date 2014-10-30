-- phpMyAdmin SQL Dump
-- version 4.0.2
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 07 Octobre 2014 à 09:08
-- Version du serveur: 5.6.11-log
-- Version de PHP: 5.3.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `beew`
--
CREATE DATABASE IF NOT EXISTS `beew` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `beew`;

-- --------------------------------------------------------

--
-- Structure de la table `actus`
--

CREATE TABLE IF NOT EXISTS `actus` (
  `id_actu` int(11) NOT NULL AUTO_INCREMENT,
  `id_langue` int(4) NOT NULL,
  `titre_actu` varchar(250) NOT NULL,
  `contenu_actu` text NOT NULL,
  `date_debut_actu` date NOT NULL,
  `date_fin_actu` date NOT NULL,
  `date_creation_actu` datetime NOT NULL,
  `rss` varchar(3) NOT NULL,
  PRIMARY KEY (`id_actu`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Contenu de la table `actus`
--

INSERT INTO `actus` (`id_actu`, `id_langue`, `titre_actu`, `contenu_actu`, `date_debut_actu`, `date_fin_actu`, `date_creation_actu`, `rss`) VALUES
(5, 1, 'Soin visage anti-Ã¢ge', '<p><a href="http://127.0.0.1/cms_tiw/roberta_v1/admin/admin.php?action=modifier_actus&amp;id_langue=1&amp;id_actu=5#" style="background-image:url(../medias/media28_g.jpg?1670)\r\n         " title="soin visage DermÃ©a">\r\n<img src="http://127.0.0.1/roberta_v1/medias/media28_g.jpg" alt="">\r\n<p>28</p>&nbsp;\r\n</a>jg kjg kgjk gk hgh hjg f ghjghj ghjg hghghghghg h ghghk hgh hg h ghg hkgg jjfbfdbdsf</p><br><br>', '2014-02-10', '2014-07-31', '2014-02-26 21:30:42', 'oui'),
(6, 1, 'Voici une deuxiÃ¨me actu', 'soin anti-cellulite [37]<br><br>', '2014-02-01', '2014-03-31', '2014-03-10 14:26:25', 'oui'),
(7, 1, 'esakddh ljsdgh kjlshlkg hs', '<iframe src="http://www.youtube.com/embed/QEYg6hRrbOo?wmode=transparent" frameborder="0"><br></iframe>', '0000-00-00', '0000-00-00', '2014-02-26 22:04:37', 'non'),
(8, 1, 'Lancement de mon super site', 'Bonjour, je lance mon super nouveau site :&nbsp; <a href="http://www.beew.fr">beew.fr</a><img src="../img/medias/media48.png" style="float: left;"><div><br><div></div><div>Charles Duron</div></div>', '2014-09-29', '2014-10-15', '2014-10-03 11:41:26', 'oui');

-- --------------------------------------------------------

--
-- Structure de la table `albums`
--

CREATE TABLE IF NOT EXISTS `albums` (
  `id_album` int(11) NOT NULL AUTO_INCREMENT,
  `titre_album` varchar(200) NOT NULL,
  `date_album` date NOT NULL,
  `id_langue` int(4) NOT NULL,
  PRIMARY KEY (`id_album`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Contenu de la table `albums`
--

INSERT INTO `albums` (`id_album`, `titre_album`, `date_album`, `id_langue`) VALUES
(1, 'tete', '2014-04-22', 1),
(2, 'fesses', '2014-04-22', 1),
(9, 'gaston', '2014-10-03', 1);

-- --------------------------------------------------------

--
-- Structure de la table `comptes`
--

CREATE TABLE IF NOT EXISTS `comptes` (
  `id_compte` int(3) NOT NULL AUTO_INCREMENT,
  `statut` varchar(30) NOT NULL,
  `nom` varchar(30) NOT NULL,
  `prenom` varchar(30) NOT NULL,
  `login` varchar(30) NOT NULL,
  `pass` blob NOT NULL,
  `fichier_compte` varchar(5) NOT NULL,
  PRIMARY KEY (`id_compte`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

--
-- Contenu de la table `comptes`
--

INSERT INTO `comptes` (`id_compte`, `statut`, `nom`, `prenom`, `login`, `pass`, `fichier_compte`) VALUES
(1, 'super_admin', '0+0', 'Toto', 'admin', 0x2a36334438354443413135454146464335384339303846443246414535304343424336304334454132, 'jpg'),
(4, 'super_admin', 'Sideshow', 'Robert', 'Tahitibob0', 0x2a38363843373933324445353238464645423944333142333037344243453246333038384146413034, 'png'),
(6, 'admin', 'Dalton', 'Jack', 'jack', 0x2a39424245453133463336303837454337433442443331414332394446413035363330364638444446, 'jpg'),
(15, 'admin', 'Dalton', 'Joe', 'joe', 0x2a39424245453133463336303837454337433442443331414332394446413035363330364638444446, 'jpg'),
(24, 'admin', 'Dalton', 'William', 'william', 0x2a39424245453133463336303837454337433442443331414332394446413035363330364638444446, 'jpg'),
(35, 'user', 'Dalton', 'Averell', 'averell', 0x2a39424245453133463336303837454337433442443331414332394446413035363330364638444446, 'jpg');

-- --------------------------------------------------------

--
-- Structure de la table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `id_contact` int(11) NOT NULL AUTO_INCREMENT,
  `prenom_contact` varchar(50) NOT NULL,
  `nom_contact` varchar(50) NOT NULL,
  `mel_contact` varchar(100) NOT NULL,
  `entreprise` varchar(50) NOT NULL,
  `objet` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `date_contact` datetime NOT NULL,
  `rep` int(3) NOT NULL,
  `id_compte` int(3) NOT NULL,
  PRIMARY KEY (`id_contact`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Contenu de la table `contacts`
--

INSERT INTO `contacts` (`id_contact`, `prenom_contact`, `nom_contact`, `mel_contact`, `entreprise`, `objet`, `message`, `date_contact`, `rep`, `id_compte`) VALUES
(5, 'sdgf', 'qfq', 'qgqg', 'qsgs', 'sgs', 'sgsg', '2014-02-11 20:14:39', 1, 0),
(6, 'dfhdfh', 'dfhdfh', 'bfbfb@fbfb.com', 'sdvf', 'sdv', 'sdvs', '2014-02-13 10:56:18', 1, 0),
(7, 'qs', 'qx', 'xcx@scs.com', 'jhscjk', 'QSX', 'sdk\r\nQSFQS\r\nQSFQSF\r\nQSF\r\rQXSC', '2014-03-02 13:10:05', 1, 1),
(8, 'qd', 'QC', 'CONTACT@hfksf.fr', 'WXWX', 'WX', 'WXWX', '2014-03-03 11:06:41', 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `css`
--

CREATE TABLE IF NOT EXISTS `css` (
  `id_css` int(4) NOT NULL AUTO_INCREMENT,
  `id_template` int(4) NOT NULL,
  `nom_css` varchar(30) NOT NULL,
  `lien_css` varchar(200) NOT NULL,
  PRIMARY KEY (`id_css`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=94 ;

--
-- Contenu de la table `css`
--

INSERT INTO `css` (`id_css`, `id_template`, `nom_css`, `lien_css`) VALUES
(88, 3, 'dermea', 'dermea_3'),
(89, 3, 'menu', 'menu_3'),
(92, 4, 'beew', ''),
(93, 4, 'front', '');

-- --------------------------------------------------------

--
-- Structure de la table `droits`
--

CREATE TABLE IF NOT EXISTS `droits` (
  `id_droit` int(4) NOT NULL AUTO_INCREMENT,
  `id_compte` int(4) NOT NULL,
  `id_module` int(4) NOT NULL,
  `valeur` int(1) NOT NULL,
  PRIMARY KEY (`id_droit`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1347 ;

--
-- Contenu de la table `droits`
--

INSERT INTO `droits` (`id_droit`, `id_compte`, `id_module`, `valeur`) VALUES
(783, 4, 8, 1),
(784, 4, 1, 1),
(785, 4, 3, 1),
(786, 4, 4, 1),
(787, 4, 5, 1),
(788, 4, 6, 1),
(789, 4, 11, 1),
(790, 4, 12, 1),
(791, 4, 7, 1),
(792, 4, 9, 1),
(793, 4, 10, 1),
(794, 4, 13, 1),
(975, 15, 8, 1),
(976, 15, 1, 0),
(977, 15, 3, 1),
(978, 15, 4, 1),
(979, 15, 5, 1),
(980, 15, 6, 1),
(981, 15, 11, 0),
(982, 15, 12, 0),
(983, 15, 7, 1),
(984, 15, 9, 1),
(985, 15, 10, 0),
(986, 15, 13, 0),
(1119, 24, 1, 1),
(1120, 24, 3, 1),
(1121, 24, 4, 1),
(1122, 24, 5, 1),
(1123, 24, 6, 1),
(1124, 24, 7, 1),
(1125, 24, 8, 1),
(1126, 24, 9, 1),
(1127, 24, 10, 1),
(1128, 24, 11, 1),
(1129, 24, 12, 1),
(1130, 24, 13, 1),
(1203, 6, 1, 1),
(1204, 6, 3, 1),
(1205, 6, 4, 1),
(1206, 6, 5, 1),
(1207, 6, 6, 1),
(1208, 6, 7, 1),
(1209, 6, 8, 1),
(1210, 6, 9, 1),
(1211, 6, 10, 1),
(1212, 6, 11, 1),
(1213, 6, 12, 1),
(1214, 6, 13, 1),
(1323, 1, 1, 1),
(1324, 1, 3, 1),
(1325, 1, 4, 1),
(1326, 1, 5, 1),
(1327, 1, 6, 1),
(1328, 1, 7, 1),
(1329, 1, 8, 1),
(1330, 1, 9, 1),
(1331, 1, 10, 1),
(1332, 1, 11, 1),
(1333, 1, 12, 1),
(1334, 1, 13, 1),
(1335, 35, 1, 0),
(1336, 35, 3, 1),
(1337, 35, 4, 1),
(1338, 35, 5, 1),
(1339, 35, 6, 1),
(1340, 35, 7, 1),
(1341, 35, 8, 1),
(1342, 35, 9, 0),
(1343, 35, 10, 1),
(1344, 35, 11, 1),
(1345, 35, 12, 1),
(1346, 35, 13, 1);

-- --------------------------------------------------------

--
-- Structure de la table `evenements`
--

CREATE TABLE IF NOT EXISTS `evenements` (
  `id_evenement` int(4) NOT NULL AUTO_INCREMENT,
  `id_langue` int(3) NOT NULL,
  `titre_evenement` varchar(100) NOT NULL,
  `contenu_evenement` text NOT NULL,
  `nature` varchar(20) NOT NULL,
  `date_debut_evenement` date NOT NULL,
  `date_fin_evenement` date NOT NULL,
  `visible` varchar(3) NOT NULL,
  PRIMARY KEY (`id_evenement`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `evenements`
--

INSERT INTO `evenements` (`id_evenement`, `id_langue`, `titre_evenement`, `contenu_evenement`, `nature`, `date_debut_evenement`, `date_fin_evenement`, `visible`) VALUES
(4, 1, 'essai kjgh ksjh lkfjklhj fkd kfhkjfdi hjdlkfj hkdfjkhjdflkjh fdj hdfjh lkch ', 'qsxqsx yf y ouyg sdouy gouy sh isoihu siohboisuoisu oisu isuoi shoifsu hoiurh oifuhoiuhpi udifh gsdgrhgjkd hggkjdfljghs dkjhgjsgjs jfhgjfs gjshgfhgkjs fhg kjsfhgrjshg kjh ghs jdh gkjsf hgkjfsh g kjhsjhg kjsdhg jkshkjgvhs kdjhgksj dhgkjs dg jsdhj ghsdj hgjsdhgkj sdhjsdh gjshgljfh gidf jgkfj hldkj g &nbsp;<br>', 'ec4bad', '2014-03-21', '2014-03-29', 'oui'),
(7, 1, 't io_yo_uy oi oiuliupiupÃ§u mÃ¹o', 'fd tufgkdhk ghk gh kghk&nbsp;', 'f7700d', '2014-03-11', '2014-03-20', 'oui');

-- --------------------------------------------------------

--
-- Structure de la table `fonts`
--

CREATE TABLE IF NOT EXISTS `fonts` (
  `id_font` int(4) NOT NULL AUTO_INCREMENT,
  `nom_font` varchar(50) NOT NULL,
  `lien_font` varchar(250) NOT NULL,
  PRIMARY KEY (`id_font`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `fonts`
--

INSERT INTO `fonts` (`id_font`, `nom_font`, `lien_font`) VALUES
(1, 'Ubuntu Condensed', '<link href=''http://fonts.googleapis.com/css?family=Ubuntu+Condensed'' rel=''stylesheet'' type=''text/css''>'),
(2, 'Titillium Web', '<link href=''http://fonts.googleapis.com/css?family=Titillium+Web:400,300'' rel=''stylesheet'' type=''text/css''>'),
(3, 'Arial', ''),
(4, 'Trebuchet MS', '');

-- --------------------------------------------------------

--
-- Structure de la table `langues`
--

CREATE TABLE IF NOT EXISTS `langues` (
  `id_langue` int(100) NOT NULL AUTO_INCREMENT,
  `pays` varchar(50) NOT NULL,
  `langue` varchar(100) NOT NULL,
  `symbole` varchar(50) NOT NULL,
  PRIMARY KEY (`id_langue`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `langues`
--

INSERT INTO `langues` (`id_langue`, `pays`, `langue`, `symbole`) VALUES
(1, 'France', 'franÃ§ais', 'FR'),
(2, 'Royaume Uni', 'anglais', 'UK');

-- --------------------------------------------------------

--
-- Structure de la table `medias`
--

CREATE TABLE IF NOT EXISTS `medias` (
  `id_media` int(3) NOT NULL AUTO_INCREMENT,
  `titre_media` varchar(250) NOT NULL,
  `alt_media` varchar(250) NOT NULL,
  `lien_media` varchar(250) NOT NULL,
  `fichier_media` varchar(5) NOT NULL,
  `type_media` int(2) NOT NULL,
  `slide` varchar(3) NOT NULL,
  `press_book` varchar(3) NOT NULL,
  PRIMARY KEY (`id_media`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=64 ;

--
-- Contenu de la table `medias`
--

INSERT INTO `medias` (`id_media`, `titre_media`, `alt_media`, `lien_media`, `fichier_media`, `type_media`, `slide`, `press_book`) VALUES
(44, 'marsu', 'le marsupilami', '', 'jpg', 1, 'non', 'oui'),
(45, 'gaston_tuyaux', 'gaston tuyaux', '', 'jpg', 1, 'oui', 'oui'),
(46, 'gaston_escargots', 'gaston escargots', '', 'jpg', 1, 'oui', 'oui'),
(47, 'gaston_idee', 'gaston idee', '', 'jpg', 1, 'oui', 'oui'),
(48, 'logo_beew', 'logo beew creation site internet et webdesign', '', 'png', 1, 'non', 'oui'),
(49, 'carte de visite recto', 'carte de visite beew recto', '', 'pdf', 2, 'non', 'non'),
(50, 'hsfr', 'hsfr', 'https://www.youtube.com/watch?v=lnORfF6XCmM', '', 4, 'non', 'non'),
(51, 'New lands', 'New lands de Justice sur l''album Access all arenas', '', 'mp3', 3, '', ''),
(52, 'Encore', 'Encore de Justice sur l''album Access all arenas', '', 'mp3', 3, '', ''),
(53, 'ogtv', 'ogtv', 'https://www.youtube.com/watch?v=8j9to3Ruvp4', '', 4, 'non', 'non'),
(63, 'cv', 'cv', '', 'pdf', 2, 'non', 'non');

-- --------------------------------------------------------

--
-- Structure de la table `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id_module` int(4) NOT NULL AUTO_INCREMENT,
  `module` varchar(50) NOT NULL,
  `action` varchar(20) NOT NULL,
  `rang` int(2) NOT NULL,
  `autorisation` varchar(5) NOT NULL,
  PRIMARY KEY (`id_module`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Contenu de la table `modules`
--

INSERT INTO `modules` (`id_module`, `module`, `action`, `rang`, `autorisation`) VALUES
(1, 'Comptes', 'comptes', 2, ''),
(3, 'Rubriques', 'rubriques', 4, 'user'),
(4, 'Pages', 'pages', 5, 'user'),
(5, 'Photos / Videos / Documents', 'medias', 6, 'user'),
(6, 'Actus', 'actus', 7, 'user'),
(7, 'Contacts', 'contacts', 10, 'user'),
(8, 'Accueil', 'intro', 1, 'user'),
(9, 'Feuilles de style', 'css', 11, ''),
(10, 'Langues', 'langues', 12, 'user'),
(11, 'Syndications', 'syndications', 8, 'user'),
(12, 'Evenements', 'evenements', 9, 'user'),
(13, 'Albums', 'albums', 13, 'user');

-- --------------------------------------------------------

--
-- Structure de la table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id_page` int(4) NOT NULL AUTO_INCREMENT,
  `id_rubrique` int(4) NOT NULL,
  `id_gabarit` int(4) NOT NULL,
  `id_compte` int(4) NOT NULL,
  `meta` varchar(250) NOT NULL,
  `keyword` varchar(3) NOT NULL,
  `liste_mots` varchar(250) NOT NULL,
  `titre_page` varchar(200) NOT NULL,
  `titre_google` varchar(200) NOT NULL,
  `visible` varchar(3) NOT NULL,
  `indexation` varchar(3) NOT NULL,
  `zone1` text NOT NULL,
  `zone2` text NOT NULL,
  `zone3` text NOT NULL,
  `date_page` date NOT NULL,
  `rang` int(3) NOT NULL,
  `url_rewriting` varchar(250) NOT NULL,
  `recherche` text NOT NULL,
  PRIMARY KEY (`id_page`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=60 ;

--
-- Contenu de la table `pages`
--

INSERT INTO `pages` (`id_page`, `id_rubrique`, `id_gabarit`, `id_compte`, `meta`, `keyword`, `liste_mots`, `titre_page`, `titre_google`, `visible`, `indexation`, `zone1`, `zone2`, `zone3`, `date_page`, `rang`, `url_rewriting`, `recherche`) VALUES
(52, 14, 2, 1, 'essai', 'oui', 'essai', 'essai', 'essai', 'non', 'oui', '<div>Lorem ipsum dolor sit amet consectetuer urna nisl est Donec tincidunt. Vitae eu id penatibus non vitae id eget penatibus Donec Morbi. Nulla nulla amet.</div><div><br></div><div>J<img src="http://127.0.0.1/roberta_v1/medias/media28_g.jpg" alt="" style="float: right;">usto et et eros et hendrerit ligula gravida magna eros et. Quis laoreet aliquam Curabitur sodales dolor et vel justo nec id. Ridiculus rhoncus id metus a vitae orci Nam Quisque nec sapien. Volutpat et laoreet tincidunt Nunc dictumst libero wisi enim orci Phasellus. Consequat id Vivamus et Sed dapibus metus Donec ante justo Morbi. Nec justo.</div><div><br></div><div>Leo Curabitur ac In enim elit montes feugiat ac consequat convallis. Id condimentum vel Vivamus eros ipsum cursus mauris vel tincidunt libero. Et et habitasse vitae tellus at eros lorem justo senectus enim. Leo condimentum fringilla sed Aliquam a eget.</div><div><br></div><div>Auctor suscipit pellentesque nonummy tincidunt congue mauris orci lorem Quisque id. Commodo quis Morbi senectus justo Quisque dignissim In congue lorem Mauris. In Morbi adipiscing urna ac pede morbi ac neque laoreet Mauris.&nbsp;</div><div><br></div>', 'wxvxwv <iframe src="//www.youtube.com/embed/5WXqw4vmwzk" allowfullscreen="" frameborder="0" height="315" width="560"></iframe><br>', '<br>', '2014-02-13', 3, 'essai-52', 'L''institut essai essai Lorem ipsum dolor sit amet consectetuer urna nisl est Donec tincidunt. Vitae eu id penatibus non vitae id eget penatibus Donec Morbi. Nulla nulla amet.Justo et et eros et hendrerit ligula gravida magna eros et. Quis laoreet aliquam Curabitur sodales dolor et vel justo nec id. Ridiculus rhoncus id metus a vitae orci Nam Quisque nec sapien. Volutpat et laoreet tincidunt Nunc dictumst libero wisi enim orci Phasellus. Consequat id Vivamus et Sed dapibus metus Donec ante justo Morbi. Nec justo.Leo Curabitur ac In enim elit montes feugiat ac consequat convallis. Id condimentum vel Vivamus eros ipsum cursus mauris vel tincidunt libero. Et et habitasse vitae tellus at eros lorem justo senectus enim. Leo condimentum fringilla sed Aliquam a eget.Auctor suscipit pellentesque nonummy tincidunt congue mauris orci lorem Quisque id. Commodo quis Morbi senectus justo Quisque dignissim In congue lorem Mauris. In Morbi adipiscing urna ac pede morbi ac neque laoreet Mauris.&nbsp; wxvxwv   essai'),
(54, 14, 1, 1, 'fgdfg', 'non', '', 'fdgdf', 'w ', 'non', 'non', 'fg', '', '', '2014-02-18', 2, 'dfgdfg-54', 'L''institut fdgdf w  fg   '),
(55, 14, 5, 1, 'essai', 'oui', 'liposuccion', 'essai', 'essai kfjk', 'oui', 'oui', 'uesomt ozihyzi ''yoizroh eohmon<iframe src="http://www.youtube.com/embed/QEYg6hRrbOo?wmode=transparent" frameborder="0"></iframe>', '', '', '2014-03-02', 1, 'essai-55', 'L''institut essai essai kfjk uesomt ozihyzi ''yoizroh eohmon   liposuccion'),
(56, 13, 2, 4, 'meta soins', 'oui', '', 'page soins', 'goo soins', 'oui', 'non', '<span style="color: rgb(143, 135, 99); font-family: ''raleway medium''; font-size: 16px; text-align: justify; background-color: rgb(255, 252, 238);">Si. Mais tendance ou pas, le rÃ©fÃ©rencement, ou Search Engine Optimisation (ou SEO, pour les intimes) se fait principalement textuellement. Perdu en pleine Silicon Valley, le petit prince n''aurait probablement pas demandÃ© Ã  monsieur Google de lui dessiner un mouton. Il aurait dÃ¹ en passer par quelque chose du genre : "image mouton". Certes moins poÃ©tique, mais dont le resultat ne fait pas de doute.</span><img src="../img/medias/media44.jpg" style="width: 20%; float: left; margin: 5% 20% 40% 0%;">', '<span style="color: rgb(143, 135, 99); font-family: ''raleway medium''; font-size: 16px; text-align: justify; background-color: rgb(255, 252, 238);">Car nous passons presque immanquablement par un intermÃ©diaire, trÃ¨s pratique, trÃ¨s perfectionnÃ© mais, avouons-le, un peu con : le moteur de recherche. Si nos amis les humains prÃ©fÃ¨rent l''image, le moteur de recherche, jusqu''Ã  prÃ©sent, n''apprÃ©cie pas Ã  sa juste valeur ce qu''il considÃ¨rera trop souvent comme un amas de tÃ¢ches de couleur. C''est au contraire dans la description dudit amas qu''il trouvera son intÃ©rÃªt (ce qui laisse effectivement Ã  penser que Google doit Ãªtre un lecteur assidu de Zola et de ses Ã©mouvantes descriptions sur 5 pages de la tapisserie murale de madame Trucmuche).</span>', '<span style="color: rgb(143, 135, 99); font-family: ''raleway medium''; font-size: 16px; text-align: justify; background-color: rgb(255, 252, 238);">Pourquoi ? Tout d''abord pour la simple raison que la reconnaissance de ce que reprÃ©sente une image exige certaines compÃ©tences qui sont, malgrÃ©&nbsp;</span><a href="http://www.pcworld.fr/internet/actualites,google-recherche-par-image-ameliore,529639,1.htm?comments=1#comments" style="margin: 0px; padding: 0px; border: 0px; font-family: ''raleway medium''; font-size: 16px; vertical-align: baseline; color: rgb(57, 95, 107); text-align: justify; background-color: rgb(255, 252, 238);">certaines avancÃ©es significatives</a><span style="color: rgb(143, 135, 99); font-family: ''raleway medium''; font-size: 16px; text-align: justify; background-color: rgb(255, 252, 238);">, Ã  l''heure actuelle trÃ¨s difficiles Ã  inculquer Ã  un robot. Celui-ci doit pouvoir extraire les Ã©lÃ©ments intÃ©ressants du contexte gÃ©nÃ©ral de l''image, dÃ©terminer quelle face de l''objet il est en train d''observer, si celui-ci est entier ou partiellement masquÃ©, etc...</span>', '2014-09-24', 1, 'page-soins', 'Nos soins page soins goo soins Si. Mais tendance ou pas, le rÃ©fÃ©rencement, ou Search Engine Optimisation (ou SEO, pour les intimes) se fait principalement textuellement. Perdu en pleine Silicon Valley, le petit prince n''aurait probablement pas demandÃ© Ã  monsieur Google de lui dessiner un mouton. Il aurait dÃ¹ en passer par quelque chose du genre : "image mouton". Certes moins poÃ©tique, mais dont le resultat ne fait pas de doute. Car nous passons presque immanquablement par un intermÃ©diaire, trÃ¨s pratique, trÃ¨s perfectionnÃ© mais, avouons-le, un peu con : le moteur de recherche. Si nos amis les humains prÃ©fÃ¨rent l''image, le moteur de recherche, jusqu''Ã  prÃ©sent, n''apprÃ©cie pas Ã  sa juste valeur ce qu''il considÃ¨rera trop souvent comme un amas de tÃ¢ches de couleur. C''est au contraire dans la description dudit amas qu''il trouvera son intÃ©rÃªt (ce qui laisse effectivement Ã  penser que Google doit Ãªtre un lecteur assidu de Zola et de ses Ã©mouvantes descriptions sur 5 pages de la tapisserie murale de madame Trucmuche). Pourquoi ? Tout d''abord pour la simple raison que la reconnaissance de ce que reprÃ©sente une image exige certaines compÃ©tences qui sont, malgrÃ©&nbsp;certaines avancÃ©es significatives, Ã  l''heure actuelle trÃ¨s difficiles Ã  inculquer Ã  un robot. Celui-ci doit pouvoir extraire les Ã©lÃ©ments intÃ©ressants du contexte gÃ©nÃ©ral de l''image, dÃ©terminer quelle face de l''objet il est en train d''observer, si celui-ci est entier ou partiellement masquÃ©, etc... '),
(57, 13, 1, 4, 'music', 'oui', '', 'musique', 'music', 'oui', 'oui', 'Encore<div><div class="no_front"><img src="../img/icones/music.png">media<img id="im51" src="../img/medias/media51.mp3" alt="New lands de Justice sur l''album Access all arenas" /></div><div class="audiojs"><audio src="../img/medias/media51.mp3" preload="auto"></audio></div></div>', '', '', '2014-10-03', 0, 'musique', 'Nos soins musique music Encoremedia   '),
(59, 13, 1, 4, 'test', 'non', '', 'test', 'test', 'oui', 'non', '<img src="../img/icones/music.png" class="no_front"><div class="audiojs"><audio src="../img/medias/media51.mp3" preload="auto"></audio></div><div><img src="../img/icones/music.png" class="no_front"><div class="audiojs"><audio src="../img/medias/media52.mp3" preload="auto"></audio></div></div>', '', '', '2014-10-06', 0, 'test', 'Nos soins test test    ');

-- --------------------------------------------------------

--
-- Structure de la table `parametres`
--

CREATE TABLE IF NOT EXISTS `parametres` (
  `id_parametre` int(5) NOT NULL AUTO_INCREMENT,
  `id_template` int(3) NOT NULL,
  `id_font` int(4) NOT NULL,
  `taille_font` varchar(10) NOT NULL,
  `titre_site` varchar(200) NOT NULL,
  `mail_retour` varchar(200) NOT NULL,
  `mail_reponse` varchar(200) NOT NULL,
  `id_theme` int(4) NOT NULL,
  `logo` varchar(200) NOT NULL,
  `favicon` varchar(200) NOT NULL,
  `objet_mail` varchar(250) NOT NULL,
  `message_mail` text NOT NULL,
  `titre_flux` varchar(250) NOT NULL,
  `description_flux` text NOT NULL,
  `rss` varchar(3) NOT NULL,
  `reseaux` varchar(3) NOT NULL,
  `liste_reseaux` text NOT NULL,
  `galerie_photos` varchar(3) NOT NULL,
  `form_contact` varchar(3) NOT NULL,
  `form_recherche` varchar(3) NOT NULL,
  `calendrier` varchar(3) NOT NULL,
  `syndication` varchar(3) NOT NULL,
  `id_page` int(3) NOT NULL,
  PRIMARY KEY (`id_parametre`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `parametres`
--

INSERT INTO `parametres` (`id_parametre`, `id_template`, `id_font`, `taille_font`, `titre_site`, `mail_retour`, `mail_reponse`, `id_theme`, `logo`, `favicon`, `objet_mail`, `message_mail`, `titre_flux`, `description_flux`, `rss`, `reseaux`, `liste_reseaux`, `galerie_photos`, `form_contact`, `form_recherche`, `calendrier`, `syndication`, `id_page`) VALUES
(1, 3, 1, '0.9vw', 'Beew - webdesign cousu main', 'contact@beew.fr', 'contact@beew.fr', 2, '../img/logo.png', '../icones/favicon.png', 'Beew - webdesign cousu main', 'Bonjour,\r\n\r\nNous avons bien pris en compte votre demande.\r\nUne rÃ©ponse personnalisÃ©e va vous Ãªtre adressÃ©e dans les meilleurs dÃ©lais.\r\n\r\nCordialement\r\n\r\nCharles Duron', 'DermÃ©a - Technologie minceur anti-Ã¢ge', 'DermÃ©a - Technologie minceur anti-Ã¢ge, la combinaison de plusieurs mÃ©thodes pour un rÃ©sultat exceptionnel', 'oui', 'oui', '[[1,"non","http://www.google.fr"],[2,"oui","http://www.google.fr"],[3,"non","http://www.google.fr"],[4,"oui","http://www.google.fr"],[5,"oui","http://www.google.fr"],[6,"non","http://www.google.fr"],[7,"non","http://www.google.fr"]]', 'oui', 'oui', 'oui', 'non', 'oui', 56);

-- --------------------------------------------------------

--
-- Structure de la table `ranger_medias`
--

CREATE TABLE IF NOT EXISTS `ranger_medias` (
  `id_ranger_media` int(11) NOT NULL AUTO_INCREMENT,
  `id_album` int(11) NOT NULL,
  `id_media` int(11) NOT NULL,
  PRIMARY KEY (`id_ranger_media`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `ranger_medias`
--

INSERT INTO `ranger_medias` (`id_ranger_media`, `id_album`, `id_media`) VALUES
(1, 1, 28),
(2, 2, 30),
(3, 2, 31),
(4, 2, 32);

-- --------------------------------------------------------

--
-- Structure de la table `rubriques`
--

CREATE TABLE IF NOT EXISTS `rubriques` (
  `id_rubrique` int(3) NOT NULL AUTO_INCREMENT,
  `id_langue` int(3) NOT NULL,
  `rubrique` varchar(30) NOT NULL,
  `rang` int(3) NOT NULL,
  PRIMARY KEY (`id_rubrique`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Contenu de la table `rubriques`
--

INSERT INTO `rubriques` (`id_rubrique`, `id_langue`, `rubrique`, `rang`) VALUES
(3, 1, 'Nos produits', 3),
(13, 1, 'Nos soins', 2),
(14, 1, 'L''institut', 1),
(17, 2, 'Brit', 1);

-- --------------------------------------------------------

--
-- Structure de la table `syndications`
--

CREATE TABLE IF NOT EXISTS `syndications` (
  `id_syndication` int(4) NOT NULL AUTO_INCREMENT,
  `id_langue` int(4) NOT NULL,
  `titre_syndication` varchar(200) NOT NULL,
  `url_syndication` varchar(250) NOT NULL,
  `nombre` int(3) NOT NULL,
  `affiche` varchar(3) NOT NULL,
  PRIMARY KEY (`id_syndication`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `syndications`
--

INSERT INTO `syndications` (`id_syndication`, `id_langue`, `titre_syndication`, `url_syndication`, `nombre`, `affiche`) VALUES
(1, 1, 'Infos santÃ©', 'http://www.sante.gouv.fr/spip.php?page=backend&id_rubrique=7', 8, 'oui');

-- --------------------------------------------------------

--
-- Structure de la table `templates`
--

CREATE TABLE IF NOT EXISTS `templates` (
  `id_template` int(11) NOT NULL AUTO_INCREMENT,
  `nom_template` varchar(50) NOT NULL,
  `date_template` date NOT NULL,
  `auteur_template` varchar(50) NOT NULL,
  PRIMARY KEY (`id_template`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `templates`
--

INSERT INTO `templates` (`id_template`, `nom_template`, `date_template`, `auteur_template`) VALUES
(3, 'Dermea', '2014-02-10', 'Olivier H'),
(4, 'beew', '2014-09-20', 'charles d');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
