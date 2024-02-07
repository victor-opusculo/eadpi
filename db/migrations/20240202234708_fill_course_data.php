<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class FillCourseData extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up(): void
    {
        $table = $this->table('courses');
        $courseDr = 
        [
            'id' => 1,
            'name' => 'Democracia e Cidadania',
            'presentation_html' => '',
            'cover_image_url' => 'https://i.imgur.com/5NC6SY2.png',
            'hours' => 40,
            'certificate_text' => 'Concluiu o curso on-line sobre Democracia e Cidadania, promovido pela Câmara Municipal de Itapevi, por meio da Escola do Parlamento "Doutor Osmar de Souza", em sua plataforma EAD, cumprindo carga horária total de 40 horas.',
            'min_points_required_on_tests' => 14,
            'is_visible' => true
        ];
        $table->insert($courseDr)->saveData();

        $table = $this->table('course_modules');
        $moduleDrs =
        [
            [
                'id' => 1,
                'course_id' => 1,
                'title' => 'Módulo 01 | A história da luta pela democracia no Brasil',
                'presentation_html' => '<div style="display: flex;">
                <div style="width: 20%;"><img style="border-radius:50%" src="https://i.imgur.com/cUtH6Kq.png" width="300" /></div>
                <div style="width: 80%; padding: 0 20px;">
                <h4><strong><small>Professor</small></strong>
                PAULO SILVINO</h4>
                <span style="font-weight: 400;">Doutor em Sociologia pela UNICAMP - Universidade Estadual de Campinas, Mestre em Sociologia pela UNESP - Universidade Estadual Paulista " Júlio de Mesquita Filho" (2010) e bacharel em Ciências Sociais-Geral pela UNICAMP - Universidade Estadual de Campinas (2006). É professor de Sociologia da FUNDAÇÃO ESCOLA DE SOCIOLOGIA E POLÍTICA DE SÃO PAULO - FESPSP (Sociologia e Política, Escola de Humanidades), integrante do NDE (Núcleo Docente Estruturante) do curso de Sociologia e Política, ex-coordenador da CPA - Comissão Própria de Avaliação e ex-coordenador do Núcleo de Pesquisa nesta mesma instituição. Exerce atividade de pesquisa com temática pertinente ao Pensamento Social Brasileiro, à Formação da Sociedade Brasileira e à Políticas Públicas. Atua como gerente de projetos e consultor nas iniciativas pública e privada, com experiência em avaliação de programas, políticas públicas e criação de indicadores.</span>
                </div></div>'
            ],
            [
                'id' => 2,
                'course_id' => 1,
                'title' => 'Módulo 02 | Democracia e Legislativo',
                'presentation_html' => '<div style="display: flex;">
                <div style="width: 20%;"><img class="alignnone size-medium wp-image-968" style="border-radius:50%" src="https://i.imgur.com/2VSHHKs.png" alt="" width="300" height="300" /></div>
                <div style="width: 80%; padding: 0 20px;">
                <h4><strong><small>Professor</small></strong></h4>
                <h4><b>Paulo Niccoli Ramirez</b></h4>
                <span style="font-weight: 400;">Doutor em Ciências Sociais (área de concentração Antropologia - 2014 PUC-SP), Mestre em Sociologia (2007 PUC-SP), bacharel e licenciatura em Ciências Sociais (2004 - PUC-SP); bacharel em Filosofia (FFLCH-USP). Professor da Sociologia e Política, Escola de Humanidades (FESPSP), da ESPM, da Casa do Saber e do Colégio São Luís. Autor do livro Sérgio Buarque de Holanda e a Dialética da Cordialidade.  Produção de textos para a Revista Humanitas (Ed. Escala) desde 2020 com duas matérias de capa. Produzo materiais e livros didáticos desde 2009. Com a editora Senac 2021, o livro sobre Ética, cidadania e Sustentabilidade Entrevistas e artigos a diferentes meios de comunicação fora e dentro do Brasil (Estadão, Folha, CBN, Globo, Tv Cultura, Record News, Rádio Camacua, TVT, Rádio Brasil Atual, Brasil de fato, GGN). Cursos sob encomenda para A Casa do Saber, Cia de Teatro BR 116, entre outros.</span>
                
                </div>
                </div>'
            ],
            [
                'id' => 3,
                'course_id' => 1,
                'title' => 'Módulo 03 | Legislativo e Sistema Eleitoral',
                'presentation_html' => '<div style="display: flex;">
                <div style="width: 20%;"><img class="alignnone wp-image-900 size-medium" style="border-radius:50%" src="https://i.imgur.com/BXU3O9n.png" alt="" width="297" height="300" /></div>
                <div style="width: 80%; padding: 0 20px;">
                <h4><strong><small>Professora</small></strong>
                TATHIANA CHICARINO</h4>
                <span style="font-weight: 400;">Doutora em Ciências Sociais pela PUC/SP (área Ciência Política). Professora de pós-graduação da Sociologia e Política – Escola de Humanidades (FESPSP) e integra a equipe acadêmica dos MBAs da mesma instituição. Pesquisadora do NEAMP PUC/SP (Núcleo de Estudo em Arte, Mídia e Política). Pesquisadora do Grupo de Pesquisa “Comunicação e Sociedade do Espetáculo” da Casper Líbero. Editora da Revista Aurora PUC/SP (Revista de Arte, Mídia e Política). Tem se dedicado aos temas: mídias e internet, cultura política, discurso político, campanha eleitoral, democracia e autoritarismo. Dentre as produções recentes destacam-se os artigos “Collectivizing political mandates: A discursive approach to the Brazilian ’s campaign in the 2018 elections” publicado na Politics Journal; e “Impeachment! Em nome do povo: uma análise discursiva da revista Veja nos governos Collor e Rousseff” na revista Mediapolis.</span>
                
                </div>
                </div>'
            ],
            [
                'id' => 4,
                'course_id' => 1,
                'title' => 'Módulo 04 | Introdução ao Processo Legislativo',
                'presentation_html' => '<div style="display: flex;">
                <div style="width: 20%;"><img class="alignnone  wp-image-974" style="border-radius:50%" src="https://i.imgur.com/GYmylfH.png" alt="" width="127" height="175" /></div>
                <div style="width: 80%; padding: 0 20px;">
                <h4><strong><small>Professor</small></strong></h4>
                <span style="font-weight: 400;">João Paulo Schwandner Ferreira</span>
                <h4><span style="font-weight: 400;">Graduado em Direito pela Pontifícia Universidade Católica de São Paulo, e Filosofia pela Universidade de São Paulo. Especialista em Direito Administrativo  e mestre em Direito Constitucional pela mesma Universidade. É professor na Fundação Escola de Sociologia e Política de São Paulo FESP-SP e advogado</span><span style="font-weight: 400;">.</span></h4>
                </div>
                </div>'
            ],
            [
                'id' => 5,
                'course_id' => 1,
                'title' => 'Módulo 05 | Votei e agora?',
                'presentation_html' => '<div style="display: flex;">
                <div style="width: 20%;"><img class="alignnone wp-image-977" style="border-radius:50%" src="https://i.imgur.com/NgnvxLc.png" alt="" width="144" height="168" /></div>
                <div style="width: 80%; padding: 0 20px;">
                <h4><strong><small>Professora</small></strong></h4>
                <b>Mércia Alves</b>
                
                <span style="font-weight: 400;">Doutora em Ciência Política pela Universidade Federal de São Carlos (PPGPOL / UFSCar). Foi pesquisadora visitante na Área de Ciência Política y de la Administración na Universidad de Salamanca, Espanha. Editora chefe da Revista Agenda Política (ISSN 2318-8499). É Mestre em Ciência Política e Bacharel em Ciências Sociais. Foi bolsista FAPESP durante o Mestrado e Doutorado. É membro dos grupos de pesquisa Comunicação Política, Partidos e Eleições (UFSCar) e do Núcleo de Estudos em Arte, Mídia e Política - NEAMP (PUC-SP). Desenvolve pesquisas na área de Ciência Política, com ênfase em Comunicação Política, principalmente nos temas: Mídia e Eleições, Campanhas Eleitorais, Profissionalização de Campanhas, Campanhas Locais, e Metodologia de Pesquisa.</span>
                
                </div>
</div>
                <!--more-->
<div style="display: flex; margin-top: 2rem;"> 
                <div style="width: 20%;"><img class="wp-image-978 alignleft" style="border-radius:50%" src="https://i.imgur.com/RUfUbT4.png" alt="" width="148" height="148" /></div>
                <div style="width: 80%; padding: 0 20px;">
                <h4><strong><small>Professora</small></strong></h4>
                <b>Isabela Kalil </b>
                <p style="text-align: left;"><span style="font-weight: 400;">Doutora em Antropologia e professora da Escola de Sociologia e Política de São Paulo. É uma das coordenadoras do Observatório da Extrema Direita (OED-Brasil) e pesquisa política, comunicação, protestos e manifestações no espaço urbano. </span></p>
                
                </div>
</div>

<div style="display: flex; margin-top: 2rem;">
                <!--more-->
                <div style="width: 20%;"><img class="wp-image-1187 size-medium alignleft" style="border-radius:50%" src="https://i.imgur.com/q5ukMiv.png" alt="" width="300" height="300" /></div>
                <div style="width: 80%; padding: 0 20px;">
                <h4><strong><small>Professor</small></strong></h4>
                <b>Rafael Castilho</b>
                
                <span style="font-weight: 400;">Sociólogo, com especialização em Gestão Pública e em Política e Relações Internacionais. É Coordenador de Projetos da Fundação Escola de Sociologia e Política de São Paulo. É também Coordenador Administrativo do MBA PPP e Concessões e do MBA Saneamento Ambiental, </span><span style="font-weight: 400;">realizados pela FESPSP, com módulo Internacional na London School of Economics and Political Science, por meio da LSE Custom Programmes.</span>
                
                </div>
</div>'                
            ]
        ];
        $table->insert($moduleDrs)->saveData();

        $table = $this->table('course_lessons');
        $lessonsDrs =
        [
            [
                'id' => 1,
                'module_id' => 1,
                'title' => 'Aula 01 – A democracia e o Brasil: uma trajetória sinuosa',
                'presentation_html' => ''
            ],
            [
                'id' => 2,
                'module_id' => 1,
                'title' => 'Aula 02 – Da República aos anos 1930: vivíamos uma democracia?',
                'presentation_html' => ''
            ],
            [
                'id' => 3,
                'module_id' => 1,
                'title' => 'Aula 03 – Entre duas rupturas democráticas: Estado Novo e 1964',
                'presentation_html' => ''
            ],
            [
                'id' => 4,
                'module_id' => 1,
                'title' => 'Aula 04 – Democracia no Brasil contemporâneo e a cultura política',
                'presentation_html' => ''
            ],
/////////////////////////////////////
            [
                'id' => 5,
                'module_id' => 2,
                'title' => 'Aula 01 – Vivemos em uma República federativa',
                'presentation_html' => ''
            ],
            [
                'id' => 6,
                'module_id' => 2,
                'title' => 'Aula 02 – O que são os três poderes?',
                'presentation_html' => ''
            ],
            [
                'id' => 7,
                'module_id' => 2,
                'title' => 'Aula 03 – Democracia representativa e participativa',
                'presentation_html' => ''
            ],
            [
                'id' => 8,
                'module_id' => 2,
                'title' => 'Aula 04 – O Legislativo na democracia moderna',
                'presentation_html' => ''
            ],
            /////////////////////////////////////
            [
                'id' => 9,
                'module_id' => 3,
                'title' => 'Aula 01 – Como funciona nosso sistema?',
                'presentation_html' => ''
            ],
            [
                'id' => 10,
                'module_id' => 3,
                'title' => 'Aula 02 – O legislativo no Brasil',
                'presentation_html' => ''
            ],
            [
                'id' => 11,
                'module_id' => 3,
                'title' => 'Aula 03 – As regras do jogo',
                'presentation_html' => ''
            ],
            [
                'id' => 12,
                'module_id' => 3,
                'title' => 'Aula 04 – Algumas reformas políticas',
                'presentation_html' => ''
            ],
            /////////////////////////////////////
            [
                'id' => 13,
                'module_id' => 4,
                'title' => 'Aula 01 – Processo legislativo: fundamentos',
                'presentation_html' => ''
            ],
            [
                'id' => 14,
                'module_id' => 4,
                'title' => 'Aula 02 – Aspectos constitucionais: a estrutura do parlamento',
                'presentation_html' => ''
            ],
            [
                'id' => 15,
                'module_id' => 4,
                'title' => 'Aula 03 – Procedimentos legislativos: a legislação',
                'presentation_html' => ''
            ],
            [
                'id' => 16,
                'module_id' => 4,
                'title' => 'Aula 04 – Procedimentos legislativos: a fiscalização',
                'presentation_html' => ''
            ],
            /////////////////////////////////////
            [
                'id' => 17,
                'module_id' => 5,
                'title' => 'Aula 01 – O dia a dia do Parlamento',
                'presentation_html' => ''
            ],
            [
                'id' => 18,
                'module_id' => 5,
                'title' => 'Aula 02 – Como acompanhar o Legislativo',
                'presentation_html' => ''
            ],
            [
                'id' => 19,
                'module_id' => 5,
                'title' => 'Aula 03 – Como acompanhar o parlamentar',
                'presentation_html' => ''
            ],
            [
                'id' => 20,
                'module_id' => 5,
                'title' => 'Aula 04 – Parte 1 – Como escolher o próximo candidato?',
                'presentation_html' => ''
            ],
            [
                'id' => 21,
                'module_id' => 5,
                'title' => 'Aula 04 – Parte 2 – Como escolher o próximo candidato?',
                'presentation_html' => ''
            ],
        ];

        $table->insert($lessonsDrs)->saveData();

        $table = $this->table('course_lesson_block');
        $blocksDrs =
        [
            //m1
            //a1
            [
                'id' => 1,
                'lesson_id' => 1,
                'title' => 'A DEMOCRACIA NO BRASIL',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'UY_E0QH6nfc'
            ],
            [
                'id' => 2,
                'lesson_id' => 1,
                'title' => 'A NATUREZA HISTÓRICA DAS DESIGUALDADES',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'SAs2V-Yn8-4'
            ],
            [
                'id' => 3,
                'lesson_id' => 1,
                'title' => 'A DEMOCRACIA É UMA ESTRADA SINUOSA',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'UozSGHZp8vI'
            ],

            //a2
            [
                'id' => 4,
                'lesson_id' => 2,
                'title' => 'O SÉCULO XIX – UMA ÉPOCA DE IDEIAS FORA DO LUGAR',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => '9ZYVtvmC1do'
            ],
            [
                'id' => 5,
                'lesson_id' => 2,
                'title' => 'CORONELISMO: RELAÇÕES DE PODER E FALSEAMENTO DO VOTO',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'qpVtvdPl6s8'
            ],
            [
                'id' => 6,
                'lesson_id' => 2,
                'title' => 'O FIM DO CORONELISMO',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'glEFmYQX3uU'
            ],

            //a3
            [
                'id' => 7,
                'lesson_id' => 3,
                'title' => 'A ERA VARGAS',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'O1Hnt9u8kKw'
            ],
            [
                'id' => 8,
                'lesson_id' => 3,
                'title' => 'O POPULISMO',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'a_Acn-ewx5c'
            ],
            [
                'id' => 9,
                'lesson_id' => 3,
                'title' => 'O GOLPE DE 64: A SEGUNDA RUPTURA',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => '1lKJU6dzadc'
            ],

            //a4
            [
                'id' => 10,
                'lesson_id' => 4,
                'title' => 'O PROCESSO DE REDEMOCRATIZAÇÃO DO PAÍS',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'k-8CxV2JBLk'
            ],
            [
                'id' => 11,
                'lesson_id' => 4,
                'title' => 'AS PRIMEIRAS ELEIÇÕES DO PERÍODO DEMOCRÁTICO',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'zLQcjlAtMko'
            ],
            [
                'id' => 12,
                'lesson_id' => 4,
                'title' => 'AVANÇOS E RETROCESSOS NA DEMOCRACIA',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'fT2Sm8ng_A0'
            ],

            //m2
            //a1
            [
                'id' => 13,
                'lesson_id' => 5,
                'title' => 'AS REPÚBLICAS',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => '7gydBwdvAIk'
            ],
            [
                'id' => 14,
                'lesson_id' => 5,
                'title' => 'REPÚBLICA FEDERATIVA',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'h268FXMa_Vg'
            ],
            [
                'id' => 15,
                'lesson_id' => 5,
                'title' => 'A REPÚBLICA FEDERATIVA NO BRASIL',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'u9Iyv_a8ZGY'
            ],
            //a2
            [
                'id' => 16,
                'lesson_id' => 6,
                'title' => 'OS TRÊS PODERES',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'XEBDCH0OR_o'
            ],
            [
                'id' => 17,
                'lesson_id' => 6,
                'title' => 'O CONTEXTO DO SURGIMENTO DOS TRÊS PODERES',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'DGUebYrFKDI'
            ],
            [
                'id' => 18,
                'lesson_id' => 6,
                'title' => 'CARACTERÍSTICAS DOS TRÊS PODERES',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'Rf99FlnlUgw'
            ],
            //a3
            [
                'id' => 19,
                'lesson_id' => 7,
                'title' => 'DEMOCRACIA REPRESENTATIVA E PARTICIPATIVA',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'IMogcCBQZS4'
            ],
            [
                'id' => 20,
                'lesson_id' => 7,
                'title' => 'CARACTERÍSTICAS DA DEMOCRACIA REPRESENTATIVA – PARTE I',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'dvhmebYHKuQ'
            ],
            [
                'id' => 21,
                'lesson_id' => 7,
                'title' => 'CARACTERÍSTICAS DA DEMOCRACIA REPRESENTATIVA – PARTE II',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'inzcdI5zAAU'
            ],
            //a4
            [
                'id' => 22,
                'lesson_id' => 8,
                'title' => 'O LEGISLATIVO NA DEMOCRACIA MODERNA',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'X2nXvqBfvGM'
            ],
            [
                'id' => 23,
                'lesson_id' => 8,
                'title' => 'NOVAS TECNOLOGIAS E O LEGISLATIVO',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => '4_JPITX2WRo'
            ],
            [
                'id' => 24,
                'lesson_id' => 8,
                'title' => 'LEGISLATIVO E DEMOCRACIA NO SÉCULO XXI',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'Yc9IeKvOLt0'
            ],
            //m3
            //a1
            [
                'id' => 25,
                'lesson_id' => 9,
                'title' => 'DEMOCRACIA E SISTEMA DE GOVERNO',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'M7fpYtPcuVg'
            ],
            [
                'id' => 26,
                'lesson_id' => 9,
                'title' => 'PARLAMENTARISMO',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'JzQphTa8LV4'
            ],
            [
                'id' => 27,
                'lesson_id' => 9,
                'title' => 'PRESIDENCIALISMO',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => '6g3d9snsd-Y'
            ],
            //a2
            [
                'id' => 28,
                'lesson_id' => 10,
                'title' => 'ELEIÇÃO: UM COMEÇO',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => '1TuVehMvdMQ'
            ],
            [
                'id' => 29,
                'lesson_id' => 10,
                'title' => 'O LEGISLATIVO E A RELAÇÃO COM O FEDERALISMO',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'KmvbZ6voLhQ'
            ],
            [
                'id' => 30,
                'lesson_id' => 10,
                'title' => 'O CRITÉRIO DE NÚMERO DE VAGAS NA REPRESENTAÇÃO LEGISLATIVA NACIONAL',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'EkNQJ1OTAG8'
            ],
            //a3
            [
                'id' => 31,
                'lesson_id' => 11,
                'title' => 'COMO VOTAMOS NOS DEPUTADOS E VEREADORES: O TIPO PROPORCIONAL',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'By4tRom_pOM'
            ],
            [
                'id' => 32,
                'lesson_id' => 11,
                'title' => 'COMO VOTAMOS NOS SENADORES: O TIPO MAJORITÁRIO',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'm6pF1GSjZmM'
            ],
            [
                'id' => 33,
                'lesson_id' => 11,
                'title' => 'A PERDA DO MANDATO',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'hYda8wZETkY'
            ],
            //a4
            [
                'id' => 34,
                'lesson_id' => 12,
                'title' => 'A QUESTÃO DA REFORMA POLÍTICA E O PAPEL DOS PARTIDOS',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => '81_Rop895xQ'
            ],
            [
                'id' => 35,
                'lesson_id' => 12,
                'title' => 'UM POUCO DE REFORMA POLÍTICA',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => '3VUm2B9P8E0'
            ],
            [
                'id' => 36,
                'lesson_id' => 12,
                'title' => 'MAIS UM POUCO DE REFORMA POLÍTICA',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'NgP7g_40l40'
            ],
            //m4
            //a1
            [
                'id' => 37,
                'lesson_id' => 13,
                'title' => 'INTRODUÇÃO ÀS LEIS',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'MvITrFS3Gec'
            ],
            [
                'id' => 38,
                'lesson_id' => 13,
                'title' => 'O SISTEMA CONSTITUCIONAL BRASILEIRO',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'qSYr7JoASJ8'
            ],
            [
                'id' => 39,
                'lesson_id' => 13,
                'title' => 'INTRODUÇÃO À ESTRUTURA DO PODER LEGISLATIVO',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => '4D_wpDtYQCs'
            ],
            //a2
            [
                'id' => 40,
                'lesson_id' => 14,
                'title' => 'ESTRUTURA DO PODER LEGISLATIVO',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'UuRhYCME7hg'
            ],
            [
                'id' => 41,
                'lesson_id' => 14,
                'title' => 'O SISTEMA DE COMPETÊNCIAS LEGISLATIVAS',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'HeTDMlh1ZdQ'
            ],
            [
                'id' => 42,
                'lesson_id' => 14,
                'title' => 'O PARLAMENTAR',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'nEXgnBRCtvY'
            ],
            //a3
            [
                'id' => 43,
                'lesson_id' => 15,
                'title' => 'PROCEDIMENTO LEGISLATIVO: INTRODUÇÃO',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'CATn3UbUuzU'
            ],
            [
                'id' => 44,
                'lesson_id' => 15,
                'title' => 'A ATIVIDADE LEGISLATIVA DO PODER EXECUTIVO',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'wAEdE0pWQMs'
            ],
            [
                'id' => 45,
                'lesson_id' => 15,
                'title' => 'INICIATIVA POPULAR',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'LnmsVrdnp_I'
            ],
            //a4
            [
                'id' => 46,
                'lesson_id' => 16,
                'title' => 'CONTROLE DA LEGISLAÇÃO: PRÉVIO E POSTERIOR',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'eAsgVxijzAE'
            ],
            [
                'id' => 47,
                'lesson_id' => 16,
                'title' => 'O CONTROLE DE LEIS E O SUPREMO TRIBUNAL FEDERAL',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'sxwv2XJfnFs'
            ],
            [
                'id' => 48,
                'lesson_id' => 16,
                'title' => 'A FUNÇÃO FISCALIZATÓRIA DO PODER LEGISLATIVO',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'XVWpD5TXl90'
            ],
            //m5
            //a1
            [
                'id' => 49,
                'lesson_id' => 17,
                'title' => 'PRINCIPAIS FUNÇÕES DOS PARLAMENTARES',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'MRHzHuU2Kn4'
            ],
            [
                'id' => 50,
                'lesson_id' => 17,
                'title' => 'PRINCIPAIS ATIVIDADES DO PARLAMENTO',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'HIycZ6T-1cA'
            ],
            [
                'id' => 51,
                'lesson_id' => 17,
                'title' => 'AGENDA DO PODER LEGISLATIVO MUNICIPAL',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'HseRGo6_YwA'
            ],
            //a2
            [
                'id' => 52,
                'lesson_id' => 18,
                'title' => 'ACESSO À INFORMAÇÃ',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'i9riFySLckM'
            ],
            [
                'id' => 53,
                'lesson_id' => 18,
                'title' => 'COMO ACOMPANHAR O LEGISLATIVO',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'awnhBUPVtgU'
            ],
            [
                'id' => 54,
                'lesson_id' => 18,
                'title' => 'COMO ACOMPANHAR O LEGISLATIVO NO MUNICÍPIO',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => '95OETZ28Liw'
            ],
            //a3
            [
                'id' => 55,
                'lesson_id' => 19,
                'title' => 'POR QUE ACOMPANHAR OS PARLAMENTARES?',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => '2DSZ9qYGNOo'
            ],
            [
                'id' => 56,
                'lesson_id' => 19,
                'title' => 'COMO ACOMPANHAR OS PARLAMENTARES',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'UzHeaGzNLZI'
            ],
            [
                'id' => 57,
                'lesson_id' => 19,
                'title' => 'COMO ACOMPANHAR OS PARLAMENTARES NO MUNICÍPIO',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'zkir-JvZspE'
            ],
            //a4-p1
            [
                'id' => 58,
                'lesson_id' => 20,
                'title' => 'COMO SABER SE DEU “MATCH”?',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'RuUiGVMS4UQ'
            ],
            [
                'id' => 59,
                'lesson_id' => 20,
                'title' => 'INFORMAÇÃO É PODER!',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'iQcTfDz81zU'
            ],
            [
                'id' => 60,
                'lesson_id' => 20,
                'title' => 'POLÍTICA SE DISCUTE SIM!',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => '6pCN8oz-usw'
            ],
            //a4-p2
            [
                'id' => 61,
                'lesson_id' => 21,
                'title' => 'COMO SABER SE DEU “MATCH”?',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'SFISKDwencY'
            ],
            [
                'id' => 62,
                'lesson_id' => 21,
                'title' => 'INFORMAÇÃO É PODER!',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => 'EqTmy3QFOvQ'
            ],
            [
                'id' => 63,
                'lesson_id' => 21,
                'title' => 'POLÍTICA SE DISCUTE SIM!',
                'presentation_html' => '',
                'video_host' => 'youtube',
                'video_url' => '_hx-2lcs95E'
            ]
        ];

        $table->insert($blocksDrs)->saveData();

        $table = $this->table('course_tests');
        $testsDrs =
        [
            [
                'id' => 1,
                'course_id' => 1,
                'linked_to_type' => 'lesson',
                'linked_to_id' => 1,
                'title' => 'Exercícios de Fixação Módulo 1 - Aula 01',
                'presentation_html' => ''
            ],
            [
                'id' => 2,
                'course_id' => 1,
                'linked_to_type' => 'lesson',
                'linked_to_id' => 2,
                'title' => 'Exercícios de Fixação Módulo 1 - Aula 02',
                'presentation_html' => ''
            ],
            [
                'id' => 3,
                'course_id' => 1,
                'linked_to_type' => 'lesson',
                'linked_to_id' => 3,
                'title' => 'Exercícios de Fixação Módulo 1 - Aula 03',
                'presentation_html' => ''
            ],
            [
                'id' => 4,
                'course_id' => 1,
                'linked_to_type' => 'lesson',
                'linked_to_id' => 4,
                'title' => 'Exercícios de Fixação Módulo 1 - Aula 04',
                'presentation_html' => ''
            ],
            //m2
            [
                'id' => 5,
                'course_id' => 1,
                'linked_to_type' => 'lesson',
                'linked_to_id' => 5,
                'title' => 'Exercícios de Fixação Módulo 2 - Aula 01',
                'presentation_html' => ''
            ],
            [
                'id' => 6,
                'course_id' => 1,
                'linked_to_type' => 'lesson',
                'linked_to_id' => 6,
                'title' => 'Exercícios de Fixação Módulo 2 - Aula 02',
                'presentation_html' => ''
            ],
            [
                'id' => 7,
                'course_id' => 1,
                'linked_to_type' => 'lesson',
                'linked_to_id' => 7,
                'title' => 'Exercícios de Fixação Módulo 2 - Aula 03',
                'presentation_html' => ''
            ],
            [
                'id' => 8,
                'course_id' => 1,
                'linked_to_type' => 'lesson',
                'linked_to_id' => 8,
                'title' => 'Exercícios de Fixação Módulo 2 - Aula 04',
                'presentation_html' => ''
            ],
            //m3
            [
                'id' => 9,
                'course_id' => 1,
                'linked_to_type' => 'lesson',
                'linked_to_id' => 9,
                'title' => 'Exercícios de Fixação Módulo 3 - Aula 01',
                'presentation_html' => ''
            ],
            [
                'id' => 10,
                'course_id' => 1,
                'linked_to_type' => 'lesson',
                'linked_to_id' => 10,
                'title' => 'Exercícios de Fixação Módulo 3 - Aula 02',
                'presentation_html' => ''
            ],
            [
                'id' => 11,
                'course_id' => 1,
                'linked_to_type' => 'lesson',
                'linked_to_id' => 11,
                'title' => 'Exercícios de Fixação Módulo 3 - Aula 03',
                'presentation_html' => ''
            ],
            [
                'id' => 12,
                'course_id' => 1,
                'linked_to_type' => 'lesson',
                'linked_to_id' => 12,
                'title' => 'Exercícios de Fixação Módulo 3 - Aula 04',
                'presentation_html' => ''
            ],
            //m4
            [
                'id' => 13,
                'course_id' => 1,
                'linked_to_type' => 'lesson',
                'linked_to_id' => 13,
                'title' => 'Exercícios de Fixação Módulo 4 - Aula 01',
                'presentation_html' => ''
            ],
            [
                'id' => 14,
                'course_id' => 1,
                'linked_to_type' => 'lesson',
                'linked_to_id' => 14,
                'title' => 'Exercícios de Fixação Módulo 4 - Aula 02',
                'presentation_html' => ''
            ],
            [
                'id' => 15,
                'course_id' => 1,
                'linked_to_type' => 'lesson',
                'linked_to_id' => 15,
                'title' => 'Exercícios de Fixação Módulo 4 - Aula 03',
                'presentation_html' => ''
            ],
            [
                'id' => 16,
                'course_id' => 1,
                'linked_to_type' => 'lesson',
                'linked_to_id' => 16,
                'title' => 'Exercícios de Fixação Módulo 4 - Aula 04',
                'presentation_html' => ''
            ],
            //m5
            [
                'id' => 17,
                'course_id' => 1,
                'linked_to_type' => 'lesson',
                'linked_to_id' => 17,
                'title' => 'Exercícios de Fixação Módulo 5 - Aula 01',
                'presentation_html' => ''
            ],
            [
                'id' => 18,
                'course_id' => 1,
                'linked_to_type' => 'lesson',
                'linked_to_id' => 18,
                'title' => 'Exercícios de Fixação Módulo 5 - Aula 02',
                'presentation_html' => ''
            ],
            [
                'id' => 19,
                'course_id' => 1,
                'linked_to_type' => 'lesson',
                'linked_to_id' => 19,
                'title' => 'Exercícios de Fixação Módulo 5 - Aula 03',
                'presentation_html' => ''
            ],
            [
                'id' => 20,
                'course_id' => 1,
                'linked_to_type' => 'lesson',
                'linked_to_id' => 21,
                'title' => 'Exercícios de Fixação Módulo 5 - Aula 04',
                'presentation_html' => ''
            ]
        ];
        $table->insert($testsDrs)->saveData();

        $table = $this->table('test_questions');
        $questionDrs =
        [
            [
                'id' => 1,
                'test_id' => 1,
                'title' => 'Sobre a democracia no Brasil é correto dizer que ela:',
                'options' => json_encode(
                    [
                        'Não existe, afinal, também não existem instituições democráticas para reger nossa  vida política, desde os tempos da escravidão.',
                        'Existe, afinal, o Brasil é um país que promove igualdade entre todos deste o século  XIX.',
                        'Existe, afinal, aqui há eleições diretas desde 1988 e, além disso, já foi possível até  impeachments',
                        'Está em construção, afinal, embora tenhamos uma constituição federal  consolidada, ainda temos muita desigualdade social.',
                        'Ela está em construção, afinal, só depois de acabarmos com a desigualdade é que  poderemos ter instituições democráticas, a exemplo de uma constituição consolidada.'
                    ]),
                'correct_answers' => json_encode([3]),
                'points' => 1
            ],
            [
                'id' => 2,
                'test_id' => 2,
                'title' => 'Em linhas gerais, o coronelismo foi:',
                'options' => json_encode(
                    [
                        'Um fenômeno político, baseado numa relação de poder do coronel sobre a  população rural mais pobre que dele dependia de algum modo. Desta relação  decorria o voto de cabresto, portanto aberto e controlado, impedindo a  participação efetiva da sociedade.',
                        'Uma relação comercial apenas entre os fazendeiros que, por um acordo, negociavam  apenas entre os que tivessem título de coronel, patente atribuída desde os tempos da  Guarda Nacional.',
                        'Um fenômeno político que se iniciou nos anos da ditadura militar, em 1964, e ainda  perdura até os dias de hoje nas áreas mais distantes dos centros urbanos.',
                        'Uma relação de mando entre coronel e seus dependentes, que se fortaleceu com a  chegada de Getúlio Vargas ao poder em 1930, afinal, Vargas também era fazendeiro.',
                        'Uma relação de mando entre coronéis e seus dependentes (daí coronelismo). Porém,  o voto secreto e autônomo sempre foi garantido, afinal, começava a República.'
                    ]),
                'correct_answers' => json_encode([0]),
                'points' => 1
            ],
            [
                'id' => 3,
                'test_id' => 3,
                'title' => 'As duas rupturas democráticas foram:',
                'options' => json_encode(
                    [
                        'O Estado Novo com João Goulart e o Golpe Militar com Marechal Deodoro.',
                        'O Golpe Militar (1937-1945) e o Estado Novo (1964-1985).',
                        'O Estado Novo (1937-1945) e o Golpe Militar (1964-1985). ',
                        'A Revolução de 1930 com Vargas e o Estado Novo (1964-1985).',
                        'O Estado Novo (1937-1985) e a Suspensão das eleições de 1989.'
                    ]),
                'correct_answers' => json_encode([2]),
                'points' => 1
            ],
            [
                'id' => 4,
                'test_id' => 4,
                'title' => 'Sobre nossa cultura política contemporânea, podemos dizer que no Brasil:',
                'options' => json_encode(
                    [
                        'Mesmo com divergências entre os grupos políticos, o debate entre as pessoas  acontece de modo respeitoso, sem afronta às instituições democráticas, ou mesmo  radicalizações.',
                        'Cada vez mais o que se vê é diminuir a presença do fantasma do autoritarismo, afinal,  as pessoas têm saído às ruas para defender o respeito às leis, o fim da bipolarização,  e a manutenção da ordem pelos militares.',
                        'As sinuosidades da democracia estão acabando, afinal, aos poucos a intolerância  com o contraditório tem dado espaço para o diálogo. Aliás, este seria o cenário que vem  se constituindo desde o início da década de 2010.',
                        'Desde 2013 não há mais condições de se discutir ou pensar na política nacional, pois  as instituições não funcionam e não permitem a vivência democrática. Pode-se dizer  que se iniciou a terceira ruptura.',
                        'A radicalização dos discursos, a dificuldade para se lidar com o contraditório,  e o desejo de alguns grupos por fechar o STF são sinais de que a sociedade ainda  vive com o fantasma do autoritarismo de outros tempos nada democráticos.'
                    ]),
                'correct_answers' => json_encode([4]),
                'points' => 1
            ],
            //m2
            [
                'id' => 5,
                'test_id' => 5,
                'title' => '“O vício enorme e radical na construção da Confederação atual está no princípio 
                da legislação para Estados ou governos em seu caráter de corporações ou 
                coletividades, em contraposição à legislação para os indivíduos que os compõem. 
                Embora não se estenda a todos os poderes conferidos à União, esse princípio invade e 
                governa aqueles de que depende a eficácia dos demais. Exceto no tocante à honra de 
                rateio, os Estados Unidos têm direito ilimitado a requisitar homens e dinheiro; mas não 
                têm autoridade para mobilizá-los por meio de normas que se estendam aos cidadãos 
                da América. A consequência é que, embora em teoria as resoluções da União referentes 
                a essas questões sejam leis que se aplicam constitucionalmente aos seus membros, na 
                prática elas são meras recomendações que os Estados podem escolher observar ou 
                desconsiderar” (Madison, Hamilton e Jay. Os Artigos Federalistas, 1787-1788. Rio de 
                Janeiro, Nova Fronteira, 1993, 160-161).
                O pensamento federalista norte-americano constitui no século XVIII um importante 
                marco para a consolidação da democracia. Leia abaixo as afirmações abaixo e assinale 
                a alternativa correta:
                I. Os princípios fundamentais do federalismo consistem na ampliação da 
                participação política, divisão dos poderes e eleição dos ocupantes dos cargos 
                legislativo e executivo.
                II. O federalismo concebeu instituições que fossem capazes de limitar a ação do 
                Estado contra as liberdades individuais.',
                'options' => json_encode(
                    [
                        'As asserções I e II são proposições verdadeiras, e a II é uma consequência da I.',
                        'As asserções I e II são proposições verdadeiras, e I é consequência da II.',
                        'A asserção I é uma proposição verdadeira, e a II é uma proposição falsa.',
                        'A asserção I é uma proposição falsa, e a II é uma proposição verdadeira.',
                        'A asserção I é uma proposição falsa, e a II é uma proposição verdadeira.'
                    ]),
                'correct_answers' => json_encode([1]),
                'points' => 1
            ],
            [
                'id' => 6,
                'test_id' => 6,
                'title' => 'A respeito dos três poderes, indique a alternativa correta:',
                'options' => json_encode(
                    [
                        'O poder legislativo é responsável pelo julgamento das leis e o judiciário pela elaboração delas.',
                        'O poder executivo e judiciários elaboram leis e o legislativo julga.',
                        'O poder legislativo elabora leis e o judiciário julga.',
                        'O poder legislativo é responsável pelas contas públicas e o judiciário executa leis.',
                        'O poder executivo julga as leis e o legislativo elabora.'
                    ]),
                'correct_answers' => json_encode([2]),
                'points' => 1
            ],
            [
                'id' => 7,
                'test_id' => 7,
                'title' => 'Os principais objetivos de uma República Federativa, da divisão dos poderes e a participação política são:',
                'options' => json_encode(
                    [
                        'Promover a tirania, os interesses coletivos e barrar os direitos individuais.',
                        'Promover modelos democráticos com divisão dos poderes e evitar a  supressão de direitos individuais.',
                        'Evitar a tirania, porém sem manter os direitos individuais. A divisão dos poderes  garante a supressão da democracia.',
                        'Promover modelos democráticos, porém sem a divisão de poderes, pois seu  objetivo é garantir os direitos individuais.',
                        'Promover modelos democráticos, porém sem a divisão de poderes, pois seu  objetivo é garantir os direitos individuais.'
                    ]),
                'correct_answers' => json_encode([1]),
                'points' => 1
            ],
            [
                'id' => 8,
                'test_id' => 8,
                'title' => 'Leia o fragmento. 
                “Verossimilhança e evidência são a matéria-prima da pós-verdade. Sua  enunciação repetida por muitos, sua expressão em imagens e memes antecipam o que  queremos ver acontecer. Sua simples difusão e circulação, a quantidade de cliques e  visualizações são o que dão legitimidade ao conteúdo que é exposto. A visibilidade  máxima, o compartilhamento, o engajamento em comentários e cliques são a forma de  legitimação da pós-verdade. Algo que não necessariamente aconteceu, mas que a  simples enunciação e circulação massiva produz um efeito de verdade”. (BENTES,  Ivana. A memética e a era da pós-verdade. Revista Cult. Disponível em:  <https://revistacult.uol.com.br/home/a-memetica-e-a-era-da-pos-verdade/>. Acesso  em: 21 jan. 2018. (Adaptado). 
                Pós-verdade foi escolhida em 2016 a palavra do ano pelo Oxford Dictionaries porque  tem sido muito utilizada para explicar um fenômeno mundial atual. De acordo com o  conceito, indique a alternativa que aponte implicações para a democracia: ',
                'options' => json_encode(
                    [
                        'Ampliação, valorização e afirmação da verdade pela possibilidade de verificação  dos acontecimentos noticiados, de modo a fortalecer os processos democráticos.',
                        'Aceleração da divulgação de fatos verdadeiros pelas redes de comunicação em  escala mundial, evitando notícias falsas que prejudiquem o funcionamento das  democracias.',
                        'Apresenta riscos aos processos democráticos, enfraquecendo a credibilidade  nos processos eleitorais.',
                        'Democratização da notícia em que os usuários participam do processo de difusão  da verdade.',
                        'Criação de consensos históricos e científicos que fortalecem a democracia.'
                    ]),
                'correct_answers' => json_encode([2]),
                'points' => 1
            ],
            //m3
            [
                'id' => 9,
                'test_id' => 9,
                'title' => 'Os sistemas de governo existentes dividem-se em:',
                'options' => json_encode(
                    [
                        'Absolutistas e anarquistas.',
                        'Monarquias e repúblicas.',
                        'Oligarquias e plutocracias.',
                        'Democracias e autocracias.',
                        'Parlamentaristas e presidencialistas.'
                    ]),
                'correct_answers' => json_encode([4]),
                'points' => 1
            ],
            [
                'id' => 10,
                'test_id' => 10,
                'title' => 'Quais são os dois critérios de disponibilização de vagas a serem disputadas nas eleições para o legislativo no Brasil?',
                'options' => json_encode(
                    [
                        'Sorteio e número de indicados pelo presidente da República.',
                        'Senadores, deputados federais e estaduais, e vereadores indicam.',
                        'Distribuição igualitária e fixa de vagas, e proporcionalidade  populacional. ',
                        'Distribuição etária e de tipo de trabalho.',
                        'Rodízio e sorteio.'
                    ]),
                'correct_answers' => json_encode([2]),
                'points' => 1
            ],
            [
                'id' => 11,
                'test_id' => 11,
                'title' => 'Quais os cargos de representação legislativa presentes no Brasil?',
                'options' => json_encode(
                    [
                        'Presidente da República, do Senado e da Câmara Federal.',
                        'Senadores, deputados federais e estaduais, e vereadores. ',
                        'Prefeitos e vereadores. ',
                        'Governadores e deputados estaduais.',
                        'Presidente da República e ministros do STF (Supremo Tribunal Federal).'
                    ]),
                'correct_answers' => json_encode([1]),
                'points' => 1
            ],
            [
                'id' => 12,
                'test_id' => 12,
                'title' => 'Abaixo elencamos algumas das principais reformas políticas implementadas nos últimos anos. Qual das opções está correta?',
                'options' => json_encode(
                    [
                        'Fim das cotas de gênero; ampliação do tempo destinado às campanhas eleitorais; ampliação do prazo para um candidato se filiar a um partido.',
                        'Fim da fidelidade partidária, das cotas de gênero e de raça.',
                        'Ampliação das coligações para as eleições proporcionais; instauração  de um partido único.',
                        'Proibição do uso da internet para propaganda eleitoral e extinção do  Fundo Especial de Financiamento de Campanhas (FEFC).',
                        'Estabelecimento de cotas; cláusula de desempenho; fim do  financiamento eleitoral por parte de empresas; diminuição do tempo de  campanha. '
                    ]),
                'correct_answers' => json_encode([4]),
                'points' => 1
            ],
            //m4
            [
                'id' => 13,
                'test_id' => 13,
                'title' => 'O que significa dizer que o nosso sistema legal é constitucional?',
                'options' => json_encode(
                    [
                        'Que a única lei é a Constituição Federal',
                        'Que não há Constituição Federal no Brasil',
                        'Que a Constituição Federal é a norma que dá fundamento às demais normas',
                        'Que a Constituição Federal é a Lei mais antiga do país',
                        'Que a Constituição Federal defende os direitos apenas da maioria'
                    ]),
                'correct_answers' => json_encode([2]),
                'points' => 1
            ],
            [
                'id' => 14,
                'test_id' => 14,
                'title' => 'Assinale a alternativa correta:',
                'options' => json_encode(
                    [
                        'Qualquer pessoa, sem qualquer requisito prévio pode candidatar-se a cargos no  Poder Legislativo.',
                        'O Poder Legislativo municipal é bicameral, formado por duas casas legislativas, a  Câmara dos Vereadores e o Senado Federal.',
                        'A competência legislativa da União Federal é absoluta, garantindo ao Congresso  Nacional a prerrogativa de legislar sobre qualquer assunto',
                        'A Constituição Federal estabelece as regras de competência legislativa de cada  ente federativo, cabendo ao município, principalmente, legislar sobre temas de  interesse local. ',
                        'Leis municipais são mais importantes que federais, prevalecendo, em caso de  confronto, em qualquer hipótese.'
                    ]),
                'correct_answers' => json_encode([3]),
                'points' => 1
            ],
            [
                'id' => 15,
                'test_id' => 15,
                'title' => 'Assinale a alternativa <b>incorreta</b>:',
                'options' => json_encode(
                    [
                        'O chefe do Poder Executivo também participa do processo legislativo de leis  ordinárias.',
                        'O Presidente da República pode vetar integralmente ou parcialmente emenda  à Constituição Federal aprovada pelo Congresso Nacional',
                        'Projetos de lei aprovados por unanimidade nas comissões em que tramitou não  necessariamente precisarão ser aprovados pelo plenário.',
                        'O veto do chefe do Poder Executivo a lei ordinária pode ser parcial ou integral e  poderá ser derrubado pela correspondente casa legislativa',
                        'As cláusulas pétreas da Constituição Federal não podem ser alteradas por meio de  emenda constitucional.'
                    ]),
                'correct_answers' => json_encode([1]),
                'points' => 1
            ],
            [
                'id' => 16,
                'test_id' => 16,
                'title' => 'Assinale a alternativa correta: ',
                'options' => json_encode(
                    [
                        'O Poder Legislativo também exerce a função de controle, que pode ser exercida  por meio das CPIs, destinadas a apurar fato determinado por prazo certo.',
                        'Os Tribunais de Contas, cortes ligadas ao Supremo Tribunal Federal, apreciam as  contas anuais apresentadas pelo chefe do Poder Executivo.',
                        'O controle de constitucionalidade, posterior à edição de uma lei, é realizado pelo  plenário do Congresso Nacional em sessão conjunta das duas casas.',
                        'Uma vez votada em plenário, não há possibilidade de controle do projeto de lei  ordinária, que passa a vigorar imediatamente após sua votação.',
                        'O Tribunal de Contas da União é a corte responsável pelo controle de  constitucionalidade de leis federais.'
                    ]),
                'correct_answers' => json_encode([0]),
                'points' => 1
            ],
            //m5
            [
                'id' => 17,
                'test_id' => 17,
                'title' => 'A partir do conteúdo assinale as alternativas corretas: 
                A. O modelo Legislativo Brasileiro é unicameral para os Estados, municípios e a nível 
                Federal
                B. Está entre as funções do poder Legislativo fiscalizar o poder Executivo
                C. Não está entre as funções do poder Legislativo a elaboração de leis
                D. É importante acompanhar o dia a dia do Legislativo pelos canais oficiais para não cair 
                em fake news.
                E. As Audiências Públicas e demais atividades do Parlamento não são abertas ao público.',
                'options' => json_encode(
                    [
                        'Alternativas A e E',
                        'Alternativas B e D ',
                        'Alternativas A e D',
                        'Alternativas C e E',
                        'Alternativas B e C'
                    ]),
                'correct_answers' => json_encode([1]),
                'points' => 1
            ],
            [
                'id' => 18,
                'test_id' => 18,
                'title' => 'A partir do conteúdo assinale as alternativas corretas:
                A. O acesso à informação é importante para garantir maior participação popular.
                B. No Brasil não há mecanismos que garantam transparência e accountability.
                C. O acesso à informação é restrito a pessoas filiadas a partidos políticos.
                D. É possível acompanhar o Legislativo da minha cidade de forma gratuita por meio de 
                sites e aplicativos.
                E. Não há meios de solicitar informação ou de fazer denúncias, caso seja observada 
                alguma irregularidade no site da Câmara Municipal de Vereadores.',
                'options' => json_encode(
                    [
                        'Alternativas A e C',
                        'Alternativas B e E',
                        'Alternativas A e D',
                        'Alternativas B e C',
                        'Alternativas D e E'
                    ]),
                'correct_answers' => json_encode([2]),
                'points' => 1
            ],
            [
                'id' => 19,
                'test_id' => 19,
                'title' => 'A partir do conteúdo assinale as alternativas corretas:
                A. O acesso à informação sobre o status das proposições que tramitam no Congresso 
                Federal é sigiloso.
                B. A agenda da Câmara municipal é de acesso público, mas os cidadãos não podem 
                acompanhar as sessões.
                C. No Brasil os gastos dos parlamentares com atividades cotidianas são divulgados 
                nos sites da Câmara dos Deputados e Senado, Assembleias Legislativas, e Câmara 
                dos Vereadores.
                D. Só podemos acompanhar o mandato dos Vereadores que ajudamos a eleger.
                E. Além do site podemos acompanhar os nossos Parlamentares por aplicativos 
                gratuitos.',
                'options' => json_encode(
                    [
                        'Alternativas A e D',
                        'Alternativas B e C',
                        'Alternativas A e E',
                        'Alternativas B e D',
                        'Alternativas C e E'
                    ]),
                'correct_answers' => json_encode([4]),
                'points' => 1
            ],
            [
                'id' => 20,
                'test_id' => 20,
                'title' => 'O plano de governo é um documento obrigatório que no qual os candidatos a  cargos do Executivo (prefeito, governador e presidente) enviam ao Tribunal  Superior Eleitoral contendo:',
                'options' => json_encode(
                    [
                        'O histórico do que fizeram nas eleições passadas',
                        'As afinidades desses políticos com suas famílias e amigos',
                        'Os planos e promessas de governo ',
                        'A relação dos aplicativos de “Tinder da política”',
                        'Documento contendo as principais fake news das eleições'
                    ]),
                'correct_answers' => json_encode([2]),
                'points' => 1
            ]
        ];
        $table->insert($questionDrs)->saveData();
    }

    public function down() : void
    {
        $this->execute('DELETE FROM test_questions');
        $this->execute('DELETE FROM course_tests');
        $this->execute('DELETE FROM course_lesson_block');
        $this->execute('DELETE FROM course_lessons');
        $this->execute('DELETE FROM course_modules');
        $this->execute('DELETE FROM courses');
    }
}
