<?php

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {




        

        // Add dummy users
        DB::table('users')->insert([
            [
                'email' => 'superadmin@gmail.com',
                'name' => 'Ali Raza',
                'slug' => '0xAliRaza',
                'website' => 'https://superadmin.com',
                'description' => 'Amet consectetur adipisicing elit. Ab quo quia doloremque porro quod atque totam asperiores nemo tempora laudantium quis sapiente qui laborum est quasi aut dolorum, animi esse. Ab quo quia doloremque porro quod atque totam asperiores nemo!',
                
                'password' => Hash::make('alimalik'),
                'role_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'email' => 'admin@gmail.com',
                'name' => 'John Doe',
                'slug' => 'john-doe',
                'website' => 'https://admin.com',
                'description' => 'Admin consectetur adipisicing elit. Ab quo quia doloremque porro quod atque totam asperiores nemo tempora laudantium quis sapiente qui laborum est quasi aut dolorum, animi esse. Ab quo quia doloremque porro quod atque totam asperiores nemo!',
                'password' => Hash::make('alimalik'),
                'role_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'email' => 'writer@gmail.com',
                'name' => 'Bud Foy',
                'slug' => 'bud-foy',
                'website' => 'https://writer.com',
                'description' => 'Writer consectetur adipisicing elit. Ab quo quia doloremque porro quod atque totam asperiores nemo tempora laudantium quis sapiente qui laborum est quasi aut dolorum, animi esse. Ab quo quia doloremque porro quod atque totam asperiores nemo!',
                'password' => Hash::make('alimalik'),
                'role_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);

        // Posts
        // DB::table('posts')->insert([
        //     [
        //         'title' => 'This is the First Dummy Post',
        //         'content' => "&lt;h1&gt;Dummy Post 1&lt;/h1&gt;&lt;h1&gt;Qu'est-ce qu'une distribution Gnu/Linux ?&lt;/h1&gt;&lt;h2&gt;Introduction&lt;/h2&gt;&lt;blockquote cite=&quot;http://dicocitations.lemonde.fr/citation_auteur_ajout/5228.php&quot;&gt; &lt;p&gt; Si vous utilisez des recettes de cuisine, vous avez probablement fait l'exp&eacute;rience de recevoir la copie d'une recette de la part d'un ami qui la partage avec vous. Et vous avez sans doute fait &eacute;galement l'exp&eacute;rience - &agrave; moins d'&ecirc;tre un n&eacute;ophyte complet - de changer cette recette. Cette modification, vous la transmettez &agrave; d'autres en faisant des copies. Un programme d'ordinateur est comme une recette de cuisine. &lt;/p&gt; &lt;footer&gt; &mdash; Richard Stallman &lt;/footer&gt;&lt;/blockquote&gt;&lt;hr&gt;&lt;h1&gt;Disposition des images&lt;/h1&gt;&lt;img class=&quot;mod left w33&quot; src=&quot;http://placehold.it/640x480&quot; alt=&quot;Capture d'&eacute;cran de Linux Mint&quot; /&gt;&lt;p&gt; La maintenance d'une distribution peut &ecirc;tre assur&eacute;e par une entreprise (cas de Red Hat Enterprise Linux, SUSE Linux Enterprise, ...) ou par une communaut&eacute; (cas de Debian, Mageia, Gentoo, Fedora, Ubuntu, Slackware, ...). Certaines communaut&eacute;s peuvent aussi avoir comme mainteneur principal une entreprise (cas de Fedora dont Red Hat est le premier sponsor, Ubuntu par Canonical ou encore OpenSUSE par Novell). &lt;br&gt; Leurs orientations particuli&egrave;res permettent des choix selon les besoins et les pr&eacute;f&eacute;rences de l'utilisateur. Certaines sont plus orient&eacute;es vers les utilisateurs d&eacute;butants (Ubuntu, Linux Mint, etc.), car plus simples &agrave; mettre en &oelig;uvre. Debian, en revanche, reste pris&eacute;e pour les serveurs ou plut&ocirc;t consid&eacute;r&eacute;e comme une m&eacute;ta-distribution, c'est-&agrave;-dire pour servir de base &agrave; une nouvelle distribution. Diverses distributions en d&eacute;rivent, comme Ubuntu, Knoppix, MEPIS&hellip; L'installation de Debian est devenue plus facile depuis la version 3.1 (Sarge), n&eacute;anmoins des comp&eacute;tences en shell et une culture des projets libres restent n&eacute;cessaires pour obtenir le GNU/Linux de ses r&ecirc;ves. &lt;br&gt; Les logiciels du projet GNU sont libres &mdash; ils utilisent tous la licence GPLv3 &mdash; Linux quant &agrave; lui est partiellement libre &mdash; sous licence GPLv2 &mdash; car il contient aussi une quantit&eacute; importante de code qui n&rsquo;est pas libre &mdash; ce sont les blobs (de). Une majorit&eacute; des logiciels contenus dans les d&eacute;p&ocirc;ts des syst&egrave;mes GNU/Linux sont libres, mais libre ne veut pas dire gratuit, m&ecirc;me si les logiciels libres sont g&eacute;n&eacute;ralement distribu&eacute;s gratuitement. Ainsi, lorsque l'on ach&egrave;te une distribution GNU/Linux, le prix pay&eacute; est celui du m&eacute;dia, de la documentation incluse et du travail effectu&eacute; pour assembler les logiciels en un tout coh&eacute;rent. Toutefois, pour se conformer aux exigences des licences utilis&eacute;es par ces logiciels, les entreprises qui &eacute;ditent ces distributions acceptent de mettre &agrave; disposition les sources des logiciels sans frais suppl&eacute;mentaires.&lt;/p&gt;&lt;hr&gt;&lt;p&gt; &copy; Copyright &lt;a href=&quot;https://fr.wikipedia.org/wiki/Distribution_GNU/Linux&quot;&gt;Wikipedia&lt;/a&gt; - Cet article est sous licence &lt;a href=&quot;https://creativecommons.org/licenses/by-sa/3.0/deed.fr&quot;&gt;CC BY-SA 3.0&lt;/a&gt;&lt;/p&gt;",
        //         'user_id' => 1,
        //         'type_id' => 1,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'title' => 'This is the Second Dummy Post',
        //         'content' => "&lt;h1&gt;Dummy Post 2&lt;/h1&gt;&lt;h1&gt;Qu'est-ce qu'une distribution Gnu/Linux ?&lt;/h1&gt;&lt;h2&gt;Introduction&lt;/h2&gt;&lt;blockquote cite=&quot;http://dicocitations.lemonde.fr/citation_auteur_ajout/5228.php&quot;&gt; &lt;p&gt; Si vous utilisez des recettes de cuisine, vous avez probablement fait l'exp&eacute;rience de recevoir la copie d'une recette de la part d'un ami qui la partage avec vous. Et vous avez sans doute fait &eacute;galement l'exp&eacute;rience - &agrave; moins d'&ecirc;tre un n&eacute;ophyte complet - de changer cette recette. Cette modification, vous la transmettez &agrave; d'autres en faisant des copies. Un programme d'ordinateur est comme une recette de cuisine. &lt;/p&gt; &lt;footer&gt; &mdash; Richard Stallman &lt;/footer&gt;&lt;/blockquote&gt;&lt;hr&gt;&lt;h1&gt;Disposition des images&lt;/h1&gt;&lt;img class=&quot;mod left w33&quot; src=&quot;http://placehold.it/640x480&quot; alt=&quot;Capture d'&eacute;cran de Linux Mint&quot; /&gt;&lt;p&gt; La maintenance d'une distribution peut &ecirc;tre assur&eacute;e par une entreprise (cas de Red Hat Enterprise Linux, SUSE Linux Enterprise, ...) ou par une communaut&eacute; (cas de Debian, Mageia, Gentoo, Fedora, Ubuntu, Slackware, ...). Certaines communaut&eacute;s peuvent aussi avoir comme mainteneur principal une entreprise (cas de Fedora dont Red Hat est le premier sponsor, Ubuntu par Canonical ou encore OpenSUSE par Novell). &lt;br&gt; Leurs orientations particuli&egrave;res permettent des choix selon les besoins et les pr&eacute;f&eacute;rences de l'utilisateur. Certaines sont plus orient&eacute;es vers les utilisateurs d&eacute;butants (Ubuntu, Linux Mint, etc.), car plus simples &agrave; mettre en &oelig;uvre. Debian, en revanche, reste pris&eacute;e pour les serveurs ou plut&ocirc;t consid&eacute;r&eacute;e comme une m&eacute;ta-distribution, c'est-&agrave;-dire pour servir de base &agrave; une nouvelle distribution. Diverses distributions en d&eacute;rivent, comme Ubuntu, Knoppix, MEPIS&hellip; L'installation de Debian est devenue plus facile depuis la version 3.1 (Sarge), n&eacute;anmoins des comp&eacute;tences en shell et une culture des projets libres restent n&eacute;cessaires pour obtenir le GNU/Linux de ses r&ecirc;ves. &lt;br&gt; Les logiciels du projet GNU sont libres &mdash; ils utilisent tous la licence GPLv3 &mdash; Linux quant &agrave; lui est partiellement libre &mdash; sous licence GPLv2 &mdash; car il contient aussi une quantit&eacute; importante de code qui n&rsquo;est pas libre &mdash; ce sont les blobs (de). Une majorit&eacute; des logiciels contenus dans les d&eacute;p&ocirc;ts des syst&egrave;mes GNU/Linux sont libres, mais libre ne veut pas dire gratuit, m&ecirc;me si les logiciels libres sont g&eacute;n&eacute;ralement distribu&eacute;s gratuitement. Ainsi, lorsque l'on ach&egrave;te une distribution GNU/Linux, le prix pay&eacute; est celui du m&eacute;dia, de la documentation incluse et du travail effectu&eacute; pour assembler les logiciels en un tout coh&eacute;rent. Toutefois, pour se conformer aux exigences des licences utilis&eacute;es par ces logiciels, les entreprises qui &eacute;ditent ces distributions acceptent de mettre &agrave; disposition les sources des logiciels sans frais suppl&eacute;mentaires.&lt;/p&gt;&lt;hr&gt;&lt;p&gt; &copy; Copyright &lt;a href=&quot;https://fr.wikipedia.org/wiki/Distribution_GNU/Linux&quot;&gt;Wikipedia&lt;/a&gt; - Cet article est sous licence &lt;a href=&quot;https://creativecommons.org/licenses/by-sa/3.0/deed.fr&quot;&gt;CC BY-SA 3.0&lt;/a&gt;&lt;/p&gt;",
        //         'user_id' => 2,
        //         'type_id' => 2,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'title' => 'This is the Third Dummy Post',
        //         'content' => "&lt;h1&gt;Dummy Post 3&lt;/h1&gt;&lt;h1&gt;Qu'est-ce qu'une distribution Gnu/Linux ?&lt;/h1&gt;&lt;h2&gt;Introduction&lt;/h2&gt;&lt;blockquote cite=&quot;http://dicocitations.lemonde.fr/citation_auteur_ajout/5228.php&quot;&gt; &lt;p&gt; Si vous utilisez des recettes de cuisine, vous avez probablement fait l'exp&eacute;rience de recevoir la copie d'une recette de la part d'un ami qui la partage avec vous. Et vous avez sans doute fait &eacute;galement l'exp&eacute;rience - &agrave; moins d'&ecirc;tre un n&eacute;ophyte complet - de changer cette recette. Cette modification, vous la transmettez &agrave; d'autres en faisant des copies. Un programme d'ordinateur est comme une recette de cuisine. &lt;/p&gt; &lt;footer&gt; &mdash; Richard Stallman &lt;/footer&gt;&lt;/blockquote&gt;&lt;hr&gt;&lt;h1&gt;Disposition des images&lt;/h1&gt;&lt;img class=&quot;mod left w33&quot; src=&quot;http://placehold.it/640x480&quot; alt=&quot;Capture d'&eacute;cran de Linux Mint&quot; /&gt;&lt;p&gt; La maintenance d'une distribution peut &ecirc;tre assur&eacute;e par une entreprise (cas de Red Hat Enterprise Linux, SUSE Linux Enterprise, ...) ou par une communaut&eacute; (cas de Debian, Mageia, Gentoo, Fedora, Ubuntu, Slackware, ...). Certaines communaut&eacute;s peuvent aussi avoir comme mainteneur principal une entreprise (cas de Fedora dont Red Hat est le premier sponsor, Ubuntu par Canonical ou encore OpenSUSE par Novell). &lt;br&gt; Leurs orientations particuli&egrave;res permettent des choix selon les besoins et les pr&eacute;f&eacute;rences de l'utilisateur. Certaines sont plus orient&eacute;es vers les utilisateurs d&eacute;butants (Ubuntu, Linux Mint, etc.), car plus simples &agrave; mettre en &oelig;uvre. Debian, en revanche, reste pris&eacute;e pour les serveurs ou plut&ocirc;t consid&eacute;r&eacute;e comme une m&eacute;ta-distribution, c'est-&agrave;-dire pour servir de base &agrave; une nouvelle distribution. Diverses distributions en d&eacute;rivent, comme Ubuntu, Knoppix, MEPIS&hellip; L'installation de Debian est devenue plus facile depuis la version 3.1 (Sarge), n&eacute;anmoins des comp&eacute;tences en shell et une culture des projets libres restent n&eacute;cessaires pour obtenir le GNU/Linux de ses r&ecirc;ves. &lt;br&gt; Les logiciels du projet GNU sont libres &mdash; ils utilisent tous la licence GPLv3 &mdash; Linux quant &agrave; lui est partiellement libre &mdash; sous licence GPLv2 &mdash; car il contient aussi une quantit&eacute; importante de code qui n&rsquo;est pas libre &mdash; ce sont les blobs (de). Une majorit&eacute; des logiciels contenus dans les d&eacute;p&ocirc;ts des syst&egrave;mes GNU/Linux sont libres, mais libre ne veut pas dire gratuit, m&ecirc;me si les logiciels libres sont g&eacute;n&eacute;ralement distribu&eacute;s gratuitement. Ainsi, lorsque l'on ach&egrave;te une distribution GNU/Linux, le prix pay&eacute; est celui du m&eacute;dia, de la documentation incluse et du travail effectu&eacute; pour assembler les logiciels en un tout coh&eacute;rent. Toutefois, pour se conformer aux exigences des licences utilis&eacute;es par ces logiciels, les entreprises qui &eacute;ditent ces distributions acceptent de mettre &agrave; disposition les sources des logiciels sans frais suppl&eacute;mentaires.&lt;/p&gt;&lt;hr&gt;&lt;p&gt; &copy; Copyright &lt;a href=&quot;https://fr.wikipedia.org/wiki/Distribution_GNU/Linux&quot;&gt;Wikipedia&lt;/a&gt; - Cet article est sous licence &lt;a href=&quot;https://creativecommons.org/licenses/by-sa/3.0/deed.fr&quot;&gt;CC BY-SA 3.0&lt;/a&gt;&lt;/p&gt;",
        //         'user_id' => 1,
        //         'type_id' => 1,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'title' => 'This is the Fourth Dummy Post',
        //         'content' => "&lt;h1&gt;Dummy Post 4&lt;/h1&gt;&lt;h1&gt;Qu'est-ce qu'une distribution Gnu/Linux ?&lt;/h1&gt;&lt;h2&gt;Introduction&lt;/h2&gt;&lt;blockquote cite=&quot;http://dicocitations.lemonde.fr/citation_auteur_ajout/5228.php&quot;&gt; &lt;p&gt; Si vous utilisez des recettes de cuisine, vous avez probablement fait l'exp&eacute;rience de recevoir la copie d'une recette de la part d'un ami qui la partage avec vous. Et vous avez sans doute fait &eacute;galement l'exp&eacute;rience - &agrave; moins d'&ecirc;tre un n&eacute;ophyte complet - de changer cette recette. Cette modification, vous la transmettez &agrave; d'autres en faisant des copies. Un programme d'ordinateur est comme une recette de cuisine. &lt;/p&gt; &lt;footer&gt; &mdash; Richard Stallman &lt;/footer&gt;&lt;/blockquote&gt;&lt;hr&gt;&lt;h1&gt;Disposition des images&lt;/h1&gt;&lt;img class=&quot;mod left w33&quot; src=&quot;http://placehold.it/640x480&quot; alt=&quot;Capture d'&eacute;cran de Linux Mint&quot; /&gt;&lt;p&gt; La maintenance d'une distribution peut &ecirc;tre assur&eacute;e par une entreprise (cas de Red Hat Enterprise Linux, SUSE Linux Enterprise, ...) ou par une communaut&eacute; (cas de Debian, Mageia, Gentoo, Fedora, Ubuntu, Slackware, ...). Certaines communaut&eacute;s peuvent aussi avoir comme mainteneur principal une entreprise (cas de Fedora dont Red Hat est le premier sponsor, Ubuntu par Canonical ou encore OpenSUSE par Novell). &lt;br&gt; Leurs orientations particuli&egrave;res permettent des choix selon les besoins et les pr&eacute;f&eacute;rences de l'utilisateur. Certaines sont plus orient&eacute;es vers les utilisateurs d&eacute;butants (Ubuntu, Linux Mint, etc.), car plus simples &agrave; mettre en &oelig;uvre. Debian, en revanche, reste pris&eacute;e pour les serveurs ou plut&ocirc;t consid&eacute;r&eacute;e comme une m&eacute;ta-distribution, c'est-&agrave;-dire pour servir de base &agrave; une nouvelle distribution. Diverses distributions en d&eacute;rivent, comme Ubuntu, Knoppix, MEPIS&hellip; L'installation de Debian est devenue plus facile depuis la version 3.1 (Sarge), n&eacute;anmoins des comp&eacute;tences en shell et une culture des projets libres restent n&eacute;cessaires pour obtenir le GNU/Linux de ses r&ecirc;ves. &lt;br&gt; Les logiciels du projet GNU sont libres &mdash; ils utilisent tous la licence GPLv3 &mdash; Linux quant &agrave; lui est partiellement libre &mdash; sous licence GPLv2 &mdash; car il contient aussi une quantit&eacute; importante de code qui n&rsquo;est pas libre &mdash; ce sont les blobs (de). Une majorit&eacute; des logiciels contenus dans les d&eacute;p&ocirc;ts des syst&egrave;mes GNU/Linux sont libres, mais libre ne veut pas dire gratuit, m&ecirc;me si les logiciels libres sont g&eacute;n&eacute;ralement distribu&eacute;s gratuitement. Ainsi, lorsque l'on ach&egrave;te une distribution GNU/Linux, le prix pay&eacute; est celui du m&eacute;dia, de la documentation incluse et du travail effectu&eacute; pour assembler les logiciels en un tout coh&eacute;rent. Toutefois, pour se conformer aux exigences des licences utilis&eacute;es par ces logiciels, les entreprises qui &eacute;ditent ces distributions acceptent de mettre &agrave; disposition les sources des logiciels sans frais suppl&eacute;mentaires.&lt;/p&gt;&lt;hr&gt;&lt;p&gt; &copy; Copyright &lt;a href=&quot;https://fr.wikipedia.org/wiki/Distribution_GNU/Linux&quot;&gt;Wikipedia&lt;/a&gt; - Cet article est sous licence &lt;a href=&quot;https://creativecommons.org/licenses/by-sa/3.0/deed.fr&quot;&gt;CC BY-SA 3.0&lt;/a&gt;&lt;/p&gt;",
        //         'user_id' => 2,
        //         'type_id' => 1,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'title' => 'This is the Fiveth Dummy Post',
        //         'title' => 'This is the First Dummy Post',
        //         'content' => "&lt;h1&gt;Dummy Post 5&lt;/h1&gt;&lt;h1&gt;Qu'est-ce qu'une distribution Gnu/Linux ?&lt;/h1&gt;&lt;h2&gt;Introduction&lt;/h2&gt;&lt;blockquote cite=&quot;http://dicocitations.lemonde.fr/citation_auteur_ajout/5228.php&quot;&gt; &lt;p&gt; Si vous utilisez des recettes de cuisine, vous avez probablement fait l'exp&eacute;rience de recevoir la copie d'une recette de la part d'un ami qui la partage avec vous. Et vous avez sans doute fait &eacute;galement l'exp&eacute;rience - &agrave; moins d'&ecirc;tre un n&eacute;ophyte complet - de changer cette recette. Cette modification, vous la transmettez &agrave; d'autres en faisant des copies. Un programme d'ordinateur est comme une recette de cuisine. &lt;/p&gt; &lt;footer&gt; &mdash; Richard Stallman &lt;/footer&gt;&lt;/blockquote&gt;&lt;hr&gt;&lt;h1&gt;Disposition des images&lt;/h1&gt;&lt;img class=&quot;mod left w33&quot; src=&quot;http://placehold.it/640x480&quot; alt=&quot;Capture d'&eacute;cran de Linux Mint&quot; /&gt;&lt;p&gt; La maintenance d'une distribution peut &ecirc;tre assur&eacute;e par une entreprise (cas de Red Hat Enterprise Linux, SUSE Linux Enterprise, ...) ou par une communaut&eacute; (cas de Debian, Mageia, Gentoo, Fedora, Ubuntu, Slackware, ...). Certaines communaut&eacute;s peuvent aussi avoir comme mainteneur principal une entreprise (cas de Fedora dont Red Hat est le premier sponsor, Ubuntu par Canonical ou encore OpenSUSE par Novell). &lt;br&gt; Leurs orientations particuli&egrave;res permettent des choix selon les besoins et les pr&eacute;f&eacute;rences de l'utilisateur. Certaines sont plus orient&eacute;es vers les utilisateurs d&eacute;butants (Ubuntu, Linux Mint, etc.), car plus simples &agrave; mettre en &oelig;uvre. Debian, en revanche, reste pris&eacute;e pour les serveurs ou plut&ocirc;t consid&eacute;r&eacute;e comme une m&eacute;ta-distribution, c'est-&agrave;-dire pour servir de base &agrave; une nouvelle distribution. Diverses distributions en d&eacute;rivent, comme Ubuntu, Knoppix, MEPIS&hellip; L'installation de Debian est devenue plus facile depuis la version 3.1 (Sarge), n&eacute;anmoins des comp&eacute;tences en shell et une culture des projets libres restent n&eacute;cessaires pour obtenir le GNU/Linux de ses r&ecirc;ves. &lt;br&gt; Les logiciels du projet GNU sont libres &mdash; ils utilisent tous la licence GPLv3 &mdash; Linux quant &agrave; lui est partiellement libre &mdash; sous licence GPLv2 &mdash; car il contient aussi une quantit&eacute; importante de code qui n&rsquo;est pas libre &mdash; ce sont les blobs (de). Une majorit&eacute; des logiciels contenus dans les d&eacute;p&ocirc;ts des syst&egrave;mes GNU/Linux sont libres, mais libre ne veut pas dire gratuit, m&ecirc;me si les logiciels libres sont g&eacute;n&eacute;ralement distribu&eacute;s gratuitement. Ainsi, lorsque l'on ach&egrave;te une distribution GNU/Linux, le prix pay&eacute; est celui du m&eacute;dia, de la documentation incluse et du travail effectu&eacute; pour assembler les logiciels en un tout coh&eacute;rent. Toutefois, pour se conformer aux exigences des licences utilis&eacute;es par ces logiciels, les entreprises qui &eacute;ditent ces distributions acceptent de mettre &agrave; disposition les sources des logiciels sans frais suppl&eacute;mentaires.&lt;/p&gt;&lt;hr&gt;&lt;p&gt; &copy; Copyright &lt;a href=&quot;https://fr.wikipedia.org/wiki/Distribution_GNU/Linux&quot;&gt;Wikipedia&lt;/a&gt; - Cet article est sous licence &lt;a href=&quot;https://creativecommons.org/licenses/by-sa/3.0/deed.fr&quot;&gt;CC BY-SA 3.0&lt;/a&gt;&lt;/p&gt;",
        //         'user_id' => 1,
        //         'type_id' => 2,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        // ]);

        // Tags
        DB::table('tags')->insert([
            [
                'name' => 'Technology',
                'slug' => 'technology',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Sex',
                'slug' => 'sex',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Something',
                'slug' => 'something',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
