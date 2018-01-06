<?php

use Illuminate\Database\Seeder;

class SubgenresSetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //TODO: convert this to use Subgenre::create instead of straight sql
        DB::raw("INSERT INTO subgenres (id, subgenre, parent_genre_id, created_by, updated_by, created_at, updated_at) VALUES
            (1,'Ambient',1,1,1,NOW(),NOW()),
            (2,'Bass',1,1,1,NOW(),NOW()),
            (3,'Chiptune',1,1,1,NOW(),NOW()),
            (4,'Drum and Bass',1,1,1,NOW(),NOW()),
            (5,'Dub',1,1,1,NOW(),NOW()),
            (6,'Dubstep',1,1,1,NOW(),NOW()),
            (7,'Electo',1,1,1,NOW(),NOW()),
            (8,'Future Bass',1,1,1,NOW(),NOW()),
            (9,'Hardstyle',1,1,1,NOW(),NOW()),
            (10,'House',1,1,1,NOW(),NOW()),
            (11,'Glitch',1,1,1,NOW(),NOW()),
            (12,'Jungle',1,1,1,NOW(),NOW()),
            (13,'Tech House',1,1,1,NOW(),NOW()),
            (14,'Acid House',1,1,1,NOW(),NOW()),
            (15,'Tropical House',1,1,1,NOW(),NOW()),
            (16,'Deep House',1,1,1,NOW(),NOW()),
            (17,'Techno',1,1,1,NOW(),NOW()),
            (18,'Trance',1,1,1,NOW(),NOW()),
            (19,'Psy Trance',1,1,1,NOW(),NOW()),
            (20,'Trap',1,1,1,NOW(),NOW()),
            (21,'Vapourwave',1,1,1,NOW(),NOW()),

            (22,'Avant Garde',2,1,1,NOW(),NOW()),
            (23,'Sound Art',2,1,1,NOW(),NOW()),
            (24,'Radio Art',2,1,1,NOW(),NOW()),
            (25,'Noise',2,1,1,NOW(),NOW()),
            (26,'Drone',2,1,1,NOW(),NOW()),
            (27,'Minimalist',2,1,1,NOW(),NOW()),
            (28,'Free Improv',2,1,1,NOW(),NOW()),

            (29,'Contemporary R&B',3,1,1,NOW(),NOW()),
            (30,'Motown',3,1,1,NOW(),NOW()),
            (31,'Neo Soul',3,1,1,NOW(),NOW()),
            (32,'Rap',3,1,1,NOW(),NOW()),
            (33,'Turntableism',3,1,1,NOW(),NOW()),

            (34,'Reggae',4,1,1,NOW(),NOW()),
            (35,'World',4,1,1,NOW(),NOW()),

            (36,'Avant-Garde',5,1,1,NOW(),NOW()),
            (37,'Baroque',5,1,1,NOW(),NOW()),
            (38,'Bebop',5,1,1,NOW(),NOW()),
            (39,'Chamber Music',5,1,1,NOW(),NOW()),
            (40,'Chant',5,1,1,NOW(),NOW()),
            (41,'Choral',5,1,1,NOW(),NOW()),
            (42,'Classical Crossover',5,1,1,NOW(),NOW()),
            (43,'Early Music',5,1,1,NOW(),NOW()),
            (44,'High Classical',5,1,1,NOW(),NOW()),
            (45,'Impressionist',5,1,1,NOW(),NOW()),
            (46,'Jazz Blues',5,1,1,NOW(),NOW()),
            (47,'Medieval',5,1,1,NOW(),NOW()),
            (48,'Minimalism',5,1,1,NOW(),NOW()),
            (49,'Modern Composition',5,1,1,NOW(),NOW()),
            (50,'Opera',5,1,1,NOW(),NOW()),
            (51,'Orchestral',5,1,1,NOW(),NOW()),
            (52,'Renaissance',5,1,1,NOW(),NOW()),
            (53,'Romantic',5,1,1,NOW(),NOW()),
            (54,'Symphonic Jazz',5,1,1,NOW(),NOW()),
            (55,'Wedding Music',5,1,1,NOW(),NOW()),

            (56,'Alternative Metal',6,1,1,NOW(),NOW()),
            (57,'Black Metal',6,1,1,NOW(),NOW()),
            (58,'Crust Punk',6,1,1,NOW(),NOW()),
            (59,'Death Metal',6,1,1,NOW(),NOW()),
            (60,'Deathcore',6,1,1,NOW(),NOW()),
            (61,'Doom Metal',6,1,1,NOW(),NOW()),
            (62,'Emo',6,1,1,NOW(),NOW()),
            (63,'Garage Punk',6,1,1,NOW(),NOW()),
            (64,'Glam Metal',6,1,1,NOW(),NOW()),
            (65,'Grindcore',6,1,1,NOW(),NOW()),
            (66,'Hardcore Punk',6,1,1,NOW(),NOW()),
            (67,'Heavy Metal',6,1,1,NOW(),NOW()),
            (68,'Folk Metal',6,1,1,NOW(),NOW()),
            (69,'Melodic Punk',6,1,1,NOW(),NOW()),
            (70,'Post-Metal',6,1,1,NOW(),NOW()),
            (71,'Power Metal',6,1,1,NOW(),NOW()),
            (72,'Progressive Metal',6,1,1,NOW(),NOW()),
            (73,'Punk',6,1,1,NOW(),NOW()),
            (74,'Punk Rock',6,1,1,NOW(),NOW()),
            (75,'Screamo',6,1,1,NOW(),NOW()),
            (76,'Speed Metal',6,1,1,NOW(),NOW()),
            (77,'Stoner Metal',6,1,1,NOW(),NOW()),
            (78,'Symphonic Metal',6,1,1,NOW(),NOW()),
            (79,'Thrash',6,1,1,NOW(),NOW()),
            (80,'Thrash Metal',6,1,1,NOW(),NOW()),

            (81,'Alternative Rock',7,1,1,NOW(),NOW()),
            (82,'College Rock',7,1,1,NOW(),NOW()),
            (83,'Classic Rock',7,1,1,NOW(),NOW()),
            (84,'Experimental Rock',7,1,1,NOW(),NOW()),
            (85,'Goth Rock',7,1,1,NOW(),NOW()),
            (86,'Grunge',7,1,1,NOW(),NOW()),
            (87,'Hard Rock',7,1,1,NOW(),NOW()),
            (88,'Indie Rock',7,1,1,NOW(),NOW()),
            (89,'Indie',7,1,1,NOW(),NOW()),
            (90,'Pop',7,1,1,NOW(),NOW()),
            (91,'90s Pop',7,1,1,NOW(),NOW()),
            (92,'Progressive Rock',7,1,1,NOW(),NOW()),
            (93,'Top 40',7,1,1,NOW(),NOW()),

            (94,'African Blues',8,1,1,NOW(),NOW()),
            (95,'Bluegrass',8,1,1,NOW(),NOW()),
            (96,'Canadiana',8,1,1,NOW(),NOW()),
            (97,'Canadian Blues',8,1,1,NOW(),NOW()),
            (98,'Chicago Blues',8,1,1,NOW(),NOW()),
            (99,'Country',8,1,1,NOW(),NOW()),
            (100,'Gospel',8,1,1,NOW(),NOW()),

            (101,'Arts and Cultiure Talk',9,1,1,NOW(),NOW()),
            (102,'Comedy',9,1,1,NOW(),NOW()),
            (103,'Lecture',9,1,1,NOW(),NOW()),
            (104,'Lifestyle',9,1,1,NOW(),NOW()),
            (105,'Daytime Talk',9,1,1,NOW(),NOW()),
            (106,'Historical Recording',9,1,1,NOW(),NOW()),
            (107,'Late Night',9,1,1,NOW(),NOW()),
            (108,'News',9,1,1,NOW(),NOW()),
            (109,'Interview',9,1,1,NOW(),NOW()),
            (110,'Sports Talk Radio',9,1,1,NOW(),NOW()),
            (111,'Talk Radio',9,1,1,NOW(),NOW())"
        );
    }
}
