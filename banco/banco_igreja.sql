create database igrejadb;
use igrejadb;

CREATE TABLE `agenda` (
  `id` int(11) NOT NULL,
  `Prioridade` tinyint(3) DEFAULT NULL,
  `Data` date DEFAULT NULL,
  `Horario` time DEFAULT NULL,
  `Assunto` varchar(50) DEFAULT '',
  `Tarefa` text DEFAULT NULL,
  `Resolvido` tinyint(1) DEFAULT 4
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

--
-- Despejando dados para a tabela `agenda`
--

INSERT INTO `agenda` (`id`, `Prioridade`, `Data`, `Horario`, `Assunto`, `Tarefa`, `Resolvido`) VALUES
(25, 2, '2018-06-18', '00:00:00', 'Manutenção de Computadores', '<p>hgij&acute;k~pho</p>', 4),
(26, 1, '2018-09-15', '19:00:00', 'Encontro de Viuvas e di orciafas', 'Teste', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `agsolucao`
--

CREATE TABLE `agsolucao` (
  `Id` int(11) NOT NULL,
  `solucao` varchar(25) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `agsolucao`
--

INSERT INTO `agsolucao` (`Id`, `solucao`) VALUES
(1, 'Nao'),
(2, 'Aguardo outros'),
(3, 'Aguardo financas'),
(4, 'Sim');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ajuda`
--

CREATE TABLE `ajuda` (
  `Id` int(11) NOT NULL,
  `pg` varchar(100) DEFAULT NULL,
  `txt` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

--
-- Despejando dados para a tabela `ajuda`
--

INSERT INTO `ajuda` (`Id`, `pg`, `txt`) VALUES
(4, 'igrejaslist.php', '<p>Este conte&uacute;do &eacute; apenas um exemplo da ajuda. Para edita-lo v&aacute; at&eacute; o menu Manuten&ccedil;&atilde;o e clique em Editar Ajuda do Sistema.&nbsp;s\\dasdfasdfasdf asd</p>'),
(5, 'membrolist.php', '<p>Para Cadastrar Membros Use o Quadrado Verde Com o Mais;</p>\r\n\r\n<p>Para Ver Mais Detalhes Sobre Os Membros Use A lupa;</p>\r\n\r\n<p>Para Editar Alguma Informa&ccedil;&atilde;o Sobre o Membro Use O Quadrado Com o L&aacute;pis;</p>\r\n\r\n<p>Para Deletar Utilize a Lixeira;</p>\r\n\r\n<p>Sr. Tesoureiro Para Lan&ccedil;ar D&iacute;zimos e Ofertas Utilizar&nbsp;<a href=\"http://localhost/igrejasistem_corrigido/caixadodialist.php\"><strong>Caixa-Lan&ccedil;amento de Hoje</strong>.</a></p>');

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `anos`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `anos` (
`anos_financ` int(4)
);

-- --------------------------------------------------------

--
-- Estrutura para tabela `audittrail`
--

CREATE TABLE `audittrail` (
  `id` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `script` varchar(255) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `table` varchar(255) DEFAULT NULL,
  `field` varchar(255) DEFAULT NULL,
  `keyvalue` longtext DEFAULT NULL,
  `oldvalue` longtext DEFAULT NULL,
  `newvalue` longtext DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `audittrail`
--

INSERT INTO `audittrail` (`id`, `datetime`, `script`, `user`, `action`, `table`, `field`, `keyvalue`, `oldvalue`, `newvalue`) VALUES
(1, '2018-07-03 01:20:42', '/login.php', 'vania', 'login', '189.81.4.12', '', '', '', ''),
(2, '2018-07-03 01:21:18', '/logout.php', 'vania', 'logout', '66.249.85.26', '', '', '', ''),
(3, '2019-05-07 15:27:32', '/login.php', 'admin', 'login', '128.201.215.198', '', '', '', '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `bancos`
--

CREATE TABLE `bancos` (
  `id` int(11) NOT NULL,
  `Banco` varchar(30) DEFAULT NULL,
  `N_do_Banco` varchar(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

--
-- Despejando dados para a tabela `bancos`
--

INSERT INTO `bancos` (`id`, `Banco`, `N_do_Banco`) VALUES
(1, 'Bradesco', '237'),
(5, 'Caixa Economica Federal', '104'),
(6, 'Itau Unibanco', '231'),
(7, 'Tesouraria', '9999'),
(8, 'Itau', '341');

-- --------------------------------------------------------

--
-- Estrutura para tabela `bens_patrimoniais`
--

CREATE TABLE `bens_patrimoniais` (
  `Id_Patri` int(11) NOT NULL,
  `Descricao` varchar(80) DEFAULT NULL,
  `DataAquisao` date DEFAULT NULL,
  `Localidade` int(11) DEFAULT NULL COMMENT 'Localidade / Cidade / Estado se imóvel',
  `Tipo` enum('M&oacute;vel','Im&oacute;vel') DEFAULT NULL,
  `Estado_do_bem` tinyint(3) DEFAULT NULL,
  `Valor_estimado` decimal(10,2) DEFAULT NULL,
  `Situacao` enum('Quitado','N&atilde;o quitado') DEFAULT NULL,
  `Anotacoes` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `bens_patrimoniais`
--

INSERT INTO `bens_patrimoniais` (`Id_Patri`, `Descricao`, `DataAquisao`, `Localidade`, `Tipo`, `Estado_do_bem`, `Valor_estimado`, `Situacao`, `Anotacoes`) VALUES
(5, 'Pulpito de Madeira', '2011-07-03', 22, 'M&oacute;vel', 1, '300.00', 'Quitado', 'Movel comprado e doado pelo pastor Andre'),
(6, '35 cadeiras de plastico', '2010-07-03', 22, 'M&oacute;vel', 1, '1050.00', 'Quitado', NULL),
(7, 'Bebedor de agua', '2011-07-03', 22, 'M&oacute;vel', 2, '600.00', 'Quitado', NULL),
(8, 'Bateria', '2011-07-03', 22, 'M&oacute;vel', 1, '1600.00', 'Quitado', NULL),
(9, 'Guitarra', '2011-07-03', 22, 'M&oacute;vel', 2, '250.00', 'Quitado', NULL),
(10, 'Datashow', '2017-09-06', 22, 'M&oacute;vel', 1, '2990.00', 'Quitado', 'Datashow epson'),
(11, '2 caixa amplificada', '2011-07-03', 22, 'M&oacute;vel', 1, '480.00', 'Quitado', NULL),
(12, 'Caixa amplificada de son', '2011-07-03', 22, 'M&oacute;vel', 1, '450.00', 'Quitado', NULL),
(13, 'Força e mesa de son', '2010-09-06', 22, 'M&oacute;vel', 1, '758.00', 'Quitado', NULL),
(14, '2 microfones sem fio', '2017-09-06', 22, 'M&oacute;vel', 1, '357.00', 'N&atilde;o quitado', NULL),
(15, '1 microfone de orelha', '2017-09-06', 22, 'M&oacute;vel', 1, '300.00', 'Quitado', NULL),
(16, '3 ventiladores', '2017-09-06', 22, 'M&oacute;vel', 1, '180.00', 'Quitado', NULL),
(17, '3 ventiladores', '2017-09-06', 22, 'M&oacute;vel', 1, '180.00', 'Quitado', NULL);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `caixadodia`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `caixadodia` (
`Tipo` tinyint(1)
,`N_Documento` varchar(20)
,`Conta_Caixa` int(11)
,`Situacao` tinyint(1)
,`Descricao` varchar(60)
,`Receitas` decimal(10,2)
,`Despesas` decimal(10,2)
,`FormaPagto` tinyint(3)
,`Dt_Lancamento` date
,`Vencimento` date
,`Centro_de_Custo` int(11)
,`id_discipulo` int(11)
,`Id` int(11)
);

-- --------------------------------------------------------

--
-- Estrutura para tabela `cargoscelulas`
--

CREATE TABLE `cargoscelulas` (
  `id_cgcelula` int(11) NOT NULL,
  `Cargo_Celula` varchar(35) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

--
-- Despejando dados para a tabela `cargoscelulas`
--

INSERT INTO `cargoscelulas` (`id_cgcelula`, `Cargo_Celula`) VALUES
(4, 'Supervisor'),
(5, 'Anfitriao'),
(6, 'Discipulador'),
(7, 'Lider'),
(8, 'Lider-Treinamento');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cargosministeriais`
--

CREATE TABLE `cargosministeriais` (
  `id_cgm` int(11) NOT NULL,
  `Cargo_Ministerial` varchar(35) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

--
-- Despejando dados para a tabela `cargosministeriais`
--

INSERT INTO `cargosministeriais` (`id_cgm`, `Cargo_Ministerial`) VALUES
(10, 'Pastor Presidente'),
(30, 'Diaconisa'),
(13, 'Pastor Vice-Presidente'),
(29, 'Diacono'),
(28, 'Presbitero'),
(18, 'Pastor Dirigente'),
(20, 'Obreiro (a)'),
(27, 'Congregado(a)'),
(26, 'Membra'),
(25, 'Membro'),
(31, 'Missionário(a)');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cartas`
--

CREATE TABLE `cartas` (
  `Id` int(11) NOT NULL,
  `Corpo_TR` text DEFAULT NULL,
  `Atualizado_TR` date DEFAULT NULL,
  `Corpo_CR` text DEFAULT NULL,
  `Atualizado_CR` date DEFAULT NULL,
  `Corpo_EX` text DEFAULT NULL,
  `Atualizado_EX` date DEFAULT NULL,
  `Corpo_Of` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Despejando dados para a tabela `cartas`
--

INSERT INTO `cartas` (`Id`, `Corpo_TR`, `Atualizado_TR`, `Corpo_CR`, `Atualizado_CR`, `Corpo_EX`, `Atualizado_EX`, `Corpo_Of`) VALUES
(1, '<table cellpadding=\"1\" cellspacing=\"10\" style=\"width:100%\">\r\n	<tbody>\r\n		<tr>\r\n			<td style=\"width:200px\"><img alt=\"\" src=\"/igrejasistem/fotosmembros/userfiles/image/Logo%20-%20Igreja%20Batista%20Liberdade_.jpg\" style=\"width:189px\" />&nbsp;&nbsp;</td>\r\n			<td>\r\n			<table border=\"0\" cellpadding=\"1\" cellspacing=\"10\" style=\"width:500px\">\r\n				<tbody>\r\n					<tr>\r\n						<td>&nbsp;Rua da flores, 15111111<br />\r\n						&nbsp;Jd. Santo Amaro - S&atilde;o Paulo - SP<br />\r\n						&nbsp;CEP: 05860-222<br />\r\n						&nbsp;CNPJ: 121564156122312313123</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td colspan=\"2\" style=\"width:100px\">\r\n			<h2><strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;CARTA DE TRANSFER&Ecirc;NCIA</strong></h2>\r\n\r\n			<p>&nbsp;</p>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td colspan=\"2\" style=\"width:100px\">\r\n			<table cellpadding=\"1\" cellspacing=\"10\" style=\"width:727px\">\r\n				<tbody>\r\n					<tr>\r\n						<td colspan=\"2\" style=\"width:100px\">\r\n						<p>&nbsp;Recebei no senhor como convem os santos Membro ativo participante dos eventos da Igreja Dizimista estar em plena comunh&atilde;o com a igreja e seu pastor..</p>\r\n\r\n						<p>Nome: &nbsp;[#nome],</p>\r\n\r\n						<p>Sexo: [#sexo],</p>\r\n\r\n						<p>Estado Civil:&nbsp;[#estadocivil],</p>\r\n\r\n						<p>Nacionalidade: [#nacionalidade],</p>\r\n\r\n						<p>CPF:&nbsp;[#cpf],</p>\r\n\r\n						<p>Cargo Ministerial: [#cargoministerial]</p>\r\n\r\n						<p>Da Igreja:&nbsp;[#daigreja]</p>\r\n\r\n						<p>Admiss&atilde;o: [#admissao]</p>\r\n\r\n						<p>RG:&nbsp;[#rg]</p>\r\n\r\n						<p>Dia:&nbsp;[#dia]</p>\r\n\r\n						<p>M&ecirc;s:&nbsp;[#mes]</p>\r\n\r\n						<p>Ano:&nbsp;[#ano]</p>\r\n						</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>', '2015-08-25', '<table cellpadding=\"1\" cellspacing=\"10\" style=\"width:100%\">\r\n	<tbody>\r\n		<tr>\r\n			<td style=\"width:200px\"><img alt=\"\" src=\"/igrejasistem/fotosmembros/userfiles/image/Logo%20-%20Igreja%20Batista%20Liberdade_.jpg\" style=\"width:189px\" />&nbsp;&nbsp;</td>\r\n			<td>\r\n			<table border=\"0\" cellpadding=\"1\" cellspacing=\"10\" style=\"width:500px\">\r\n				<tbody>\r\n					<tr>\r\n						<td>&nbsp;Rua da flores, 15111111<br />\r\n						&nbsp;Jd. Santo Amaro - S&atilde;o Paulo - SP<br />\r\n						&nbsp;CEP: 05860-222<br />\r\n						&nbsp;CNPJ: 121564156122312313123</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td colspan=\"2\" style=\"width:100px\">\r\n			<h2><strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;CARTA DE RECOMENDA&Ccedil;&Atilde;O</strong></h2>\r\n\r\n			<p>&nbsp;</p>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td colspan=\"2\" style=\"width:100px\">\r\n			<table cellpadding=\"1\" cellspacing=\"10\" style=\"width:727px\">\r\n				<tbody>\r\n					<tr>\r\n						<td colspan=\"2\" style=\"width:100px\">\r\n						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean non lacus sed dui cursus egestas et ac dolor. Nam fringilla laoreet arcu, elementum sagittis urna suscipit vel. Proin et molestie ante. Praesent posuere lacus ut nulla gravida vestibulum. In in urna eu eros lobortis consequat. Curabitur nec dictum nisl. Fusce interdum ultricies massa, et dapibus mauris vehicula vel. Donec posuere lobortis rutrum.</p>\r\n\r\n						<p>Nome: &nbsp;[#nome],</p>\r\n\r\n						<p>Sexo: [#sexo],</p>\r\n\r\n						<p>Estado Civil:&nbsp;[#estadocivil],</p>\r\n\r\n						<p>Nacionalidade: [#nacionalidade],</p>\r\n\r\n						<p>CPF:&nbsp;[#cpf],</p>\r\n\r\n						<p>Cargo Ministerial: [#cargoministerial]</p>\r\n\r\n						<p>Da Igreja:&nbsp;[#daigreja]</p>\r\n\r\n						<p>Admiss&atilde;o: [#admissao]</p>\r\n\r\n						<p>RG:&nbsp;[#rg]</p>\r\n\r\n						<p>Dia:&nbsp;[#dia]</p>\r\n\r\n						<p>M&ecirc;s:&nbsp;[#mes]</p>\r\n\r\n						<p>Ano:&nbsp;[#ano]</p>\r\n						</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>', '2015-08-25', '<table cellpadding=\"1\" cellspacing=\"10\" style=\"width:100%\">\r\n	<tbody>\r\n		<tr>\r\n			<td style=\"width:200px\"><img alt=\"\" src=\"/igrejasistem/fotosmembros/userfiles/image/Logo%20-%20Igreja%20Batista%20Liberdade_.jpg\" style=\"width:189px\" />&nbsp;&nbsp;</td>\r\n			<td>\r\n			<table border=\"0\" cellpadding=\"1\" cellspacing=\"10\" style=\"width:500px\">\r\n				<tbody>\r\n					<tr>\r\n						<td>&nbsp;Rua da flores, 15111111<br />\r\n						&nbsp;Jd. Santo Amaro - S&atilde;o Paulo - SP<br />\r\n						&nbsp;CEP: 05860-222<br />\r\n						&nbsp;CNPJ: 121564156122312313123</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td colspan=\"2\" style=\"width:100px\">\r\n			<h2><strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;CARTA DE DESLIGAMENTO</strong></h2>\r\n\r\n			<p>&nbsp;</p>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td colspan=\"2\" style=\"width:100px\">\r\n			<table cellpadding=\"1\" cellspacing=\"10\" style=\"width:727px\">\r\n				<tbody>\r\n					<tr>\r\n						<td colspan=\"2\" style=\"width:100px\">\r\n						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean non lacus sed dui cursus egestas et ac dolor. Nam fringilla laoreet arcu, elementum sagittis urna suscipit vel. Proin et molestie ante. Praesent posuere lacus ut nulla gravida vestibulum. In in urna eu eros lobortis consequat. Curabitur nec dictum nisl. Fusce interdum ultricies massa, et dapibus mauris vehicula vel. Donec posuere lobortis rutrum.</p>\r\n\r\n						<p>Nome: &nbsp;[#nome],</p>\r\n\r\n						<p>Sexo: [#sexo],</p>\r\n\r\n						<p>Estado Civil:&nbsp;[#estadocivil],</p>\r\n\r\n						<p>Nacionalidade: [#nacionalidade],</p>\r\n\r\n						<p>CPF:&nbsp;[#cpf],</p>\r\n\r\n						<p>Cargo Ministerial: [#cargoministerial]</p>\r\n\r\n						<p>Da Igreja:&nbsp;[#daigreja]</p>\r\n\r\n						<p>Admiss&atilde;o: [#admissao]</p>\r\n\r\n						<p>RG:&nbsp;[#rg]</p>\r\n\r\n						<p>Dia:&nbsp;[#dia]</p>\r\n\r\n						<p>M&ecirc;s:&nbsp;[#mes]</p>\r\n\r\n						<p>Ano:&nbsp;[#ano]</p>\r\n						</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>', '2015-08-25', '<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Of. N&deg;_______/___&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_________, de _______de____.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&Aacute;</p>\r\n\r\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;________________________________.</p>\r\n\r\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ilm&ordm;. Sr.&nbsp;_________________________.</p>\r\n\r\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Secretaria _______________________&shy;.</p>\r\n\r\n<p>__________-____</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>A igreja<strong>&nbsp;_______________________&nbsp;</strong>&eacute; uma organiza&ccedil;&atilde;o religiosa, sem finslucrativos, criada com o objetivo de pregar o evangelho de nosso Senhor Jesus Cristo, realizar obras de Assist&ecirc;ncia Social e Educacional junto &agrave; Comunidade, colaborando com as entidades governamentais para o desenvolvimento de uma sociedade pac&iacute;fica, ordeira e progressista.</p>\r\n\r\n<p>Tendo em vista a realiza&ccedil;&atilde;o de&nbsp;<strong>um ______________________________desta Igreja,&nbsp;</strong>onde estaremos fazendo apresenta&ccedil;&otilde;es como ___________________, solicitamos desta secretaria&nbsp;&nbsp;a<strong>libera&ccedil;&atilde;o&nbsp;&nbsp;da (rua) ____________________________</strong>&nbsp;para realiz&ccedil;&atilde;o deste evento. Com &iacute;nicio apartir das ______ at&eacute; as ______ no (dia da semana) dia ___ de _______________ do corrente ano.</p>\r\n\r\n<p>Certo de sua aten&ccedil;&atilde;o e pronta acolhida, aproveitamos a oportunidade para apresentar-lhe os protestos de nosso respeito e apre&ccedil;o.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>(assinatura do pastor)_______________________________</p>\r\n\r\n<p>(nome do pastor)</p>\r\n\r\n<p>(Pastor Presidente)</p>');

-- --------------------------------------------------------

--
-- Estrutura para tabela `celulas`
--

CREATE TABLE `celulas` (
  `Id_celula` int(11) NOT NULL,
  `Responsavel` varchar(60) DEFAULT NULL,
  `NomeCelula` varchar(60) DEFAULT NULL,
  `DiasReunioes` set('Domingo','Segunda','Terca','Quarta','Quinta','Sexta','Sabado') DEFAULT 'Sabado',
  `HorarioReunioes` time DEFAULT NULL,
  `Endereco` varchar(80) DEFAULT NULL,
  `Anotacoes` varchar(255) DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `celulas`
--

INSERT INTO `celulas` (`Id_celula`, `Responsavel`, `NomeCelula`, `DiasReunioes`, `HorarioReunioes`, `Endereco`, `Anotacoes`) VALUES
(10, 'Vania Alcantara', 'Célula Fhiladélfia', 'Segunda', '20:00:00', NULL, 'Celula Tudas as segundas venha no visitar.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `contatos`
--

CREATE TABLE `contatos` (
  `Id` int(11) NOT NULL,
  `Pessoa_Empresa` varchar(70) DEFAULT NULL,
  `Telefone_1` varchar(25) NOT NULL DEFAULT '',
  `Telefone_2` varchar(25) DEFAULT NULL,
  `Celular_1` varchar(25) DEFAULT NULL,
  `Celular_2` varchar(25) DEFAULT NULL,
  `EnderecoCompleto` varchar(500) DEFAULT NULL COMMENT 'Pressione Shif + Enter',
  `EmailPessoal` varchar(65) DEFAULT NULL,
  `EmailComercial` varchar(65) DEFAULT NULL,
  `Anotacoes` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estrutura para tabela `conta_bancaria`
--

CREATE TABLE `conta_bancaria` (
  `Id` int(11) NOT NULL,
  `Banco` int(11) DEFAULT NULL,
  `Agencia` varchar(10) DEFAULT NULL,
  `Conta` varchar(20) DEFAULT NULL,
  `Gerente` varchar(40) DEFAULT NULL,
  `Telefone` varchar(50) DEFAULT NULL,
  `Limite` decimal(10,2) DEFAULT NULL,
  `Site` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `controle_tarefas`
--

CREATE TABLE `controle_tarefas` (
  `Id_tarefas` int(11) NOT NULL,
  `DuracaoEstimada` varchar(50) DEFAULT NULL,
  `Descricao` varchar(100) DEFAULT NULL,
  `Prioridade` tinyint(1) DEFAULT NULL,
  `Anotacoes` varchar(255) DEFAULT NULL,
  `Completa` tinyint(1) DEFAULT NULL,
  `Concluida_em` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Despejando dados para a tabela `controle_tarefas`
--

INSERT INTO `controle_tarefas` (`Id_tarefas`, `DuracaoEstimada`, `Descricao`, `Prioridade`, `Anotacoes`, `Completa`, `Concluida_em`) VALUES
(3, 'Uma hora e meia', 'Quinta - Feira: Culto Coluna de Oração', 1, 'Dirigente do Culto: Pastora Nilza\r\nPregador: Pastora Nilza\r\nPorteiro: Pastor Rafael.', 1, '2018-07-26');

-- --------------------------------------------------------

--
-- Estrutura para tabela `dias_semana`
--

CREATE TABLE `dias_semana` (
  `Id_semana` tinyint(3) NOT NULL,
  `Dias` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `dias_semana`
--

INSERT INTO `dias_semana` (`Id_semana`, `Dias`) VALUES
(1, 'Domingo'),
(2, 'Segunda'),
(3, 'Terca'),
(4, 'Quarta'),
(5, 'Quinta'),
(6, 'Sexta'),
(7, 'Sabado');

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `dizimos`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `dizimos` (
`Tipo` tinyint(1)
,`Conta_Caixa` int(11)
,`Situacao` tinyint(1)
,`Descricao` varchar(60)
,`Receitas` decimal(10,2)
,`FormaPagto` tinyint(3)
,`Dt_Lancamento` date
,`Vencimento` date
,`Centro_de_Custo` int(11)
,`id_discipulo` int(11)
,`Id` int(11)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `dizimosmesatual`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `dizimosmesatual` (
`Tipo` tinyint(1)
,`Conta_Caixa` int(11)
,`Situacao` tinyint(1)
,`Descricao` varchar(60)
,`Receitas` decimal(10,2)
,`FormaPagto` tinyint(3)
,`Dt_Lancamento` date
,`Vencimento` date
,`Centro_de_Custo` int(11)
,`id_discipulo` int(11)
,`Id` int(11)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `dizimosporcriterio`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `dizimosporcriterio` (
`Mes` int(2)
,`Tipo` tinyint(1)
,`Conta_Caixa` int(11)
,`Situacao` tinyint(1)
,`Descricao` varchar(60)
,`Receitas` decimal(10,2)
,`FormaPagto` tinyint(3)
,`Dt_Lancamento` date
,`Vencimento` date
,`Centro_de_Custo` int(11)
,`id_discipulo` int(11)
,`Id` int(11)
);

-- --------------------------------------------------------

--
-- Estrutura para tabela `escolaridade`
--

CREATE TABLE `escolaridade` (
  `Id` int(11) NOT NULL,
  `Escolaridade` varchar(40) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `escolaridade`
--

INSERT INTO `escolaridade` (`Id`, `Escolaridade`) VALUES
(1, 'Ensino medio Completo'),
(2, 'Ensino Medio Incompleto'),
(3, 'Nivel Superior'),
(4, 'Nivel Superior Incompleto');

-- --------------------------------------------------------

--
-- Estrutura para tabela `estado_patrimonio`
--

CREATE TABLE `estado_patrimonio` (
  `Id_est_patri` int(11) NOT NULL,
  `Estado_do_Bem` varchar(20) DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Despejando dados para a tabela `estado_patrimonio`
--

INSERT INTO `estado_patrimonio` (`Id_est_patri`, `Estado_do_Bem`) VALUES
(1, 'Novo'),
(2, 'Bom'),
(3, 'Regular'),
(4, 'Ruim');

-- --------------------------------------------------------

--
-- Estrutura para tabela `estudos_biblicos`
--

CREATE TABLE `estudos_biblicos` (
  `Id_estu_bb` int(11) NOT NULL,
  `Numero_do_Estudo` int(11) DEFAULT NULL,
  `Data_do_Estudo` date DEFAULT NULL,
  `Assunto` varchar(100) DEFAULT NULL,
  `DescricaoMensagem` text DEFAULT NULL,
  `Anotacoes` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `estudos_biblicos`
--

INSERT INTO `estudos_biblicos` (`Id_estu_bb`, `Numero_do_Estudo`, `Data_do_Estudo`, `Assunto`, `DescricaoMensagem`, `Anotacoes`) VALUES
(4, 2, '2018-07-29', 'DINÂMICA SOBRE PECADO', '<p><strong>Objetivo:</strong>&nbsp;Mostrar que o pecado &eacute; a desobedi&ecirc;ncia quanto as leis de Deus.</p>\r\n\r\n<p><strong>Material</strong>: Um papel; Uma caneta; Uma venda.</p>\r\n\r\n<p><strong>Desenvolvimento:</strong>&nbsp;Ser&aacute; escolhido um volunt&aacute;rio entre a turma e este ter&aacute; os seus olhos vendados, assim que os seus olhos estiverem vendados, ele seguir&aacute; em p&eacute; enquanto a turma seguir&aacute; sentada. O aplicador ent&atilde;o pegar&aacute; o papel e escrever&aacute;: &ldquo;Ficar em p&eacute; &eacute; pecado&rdquo;, ap&oacute;s isso, o aplicador senta e passa o papel pra cada um participante de forma discreta e pedindo que n&atilde;o leia em voz alta. Assim que todos lerem, o aplicador, ainda sentado, pergunta ao que est&aacute; vendado se ele est&aacute; cometendo algum pecado naquele momento, obviamente ele responder&aacute; que n&atilde;o, a&iacute; &eacute; a vez do aplicador pedir ao volunt&aacute;rio tirar a venda e assim que ele tirar dar&aacute; o papel a ele.</p>\r\n\r\n<p><strong>Moral:</strong>&nbsp;Muitas das vezes estamos cegos e custamos a enxergar o nosso pecado, mas Cristo &eacute; o &uacute;nico capaz de nos perdoar e abrir os nossos olhos para que venhamos a andar em seus caminhos.</p>\r\n\r\n<p><strong>Refer&ecirc;ncia B&iacute;blica:</strong>&nbsp;Jo&atilde;o 9:35-41</p>', '1.1'),
(3, 1, '2018-07-29', 'Quem é o Espírito Santo - Diáconisa da Edilelma.', '<p><strong>Quem &eacute; o Esp&iacute;rito Santo?</strong></p>\r\n\r\n<ol>\r\n	<li><strong>O Esp&iacute;rito Santo &eacute; uma pessoa</strong></li>\r\n</ol>\r\n\r\n<p>O Esp&iacute;rito Santo &eacute; uma pessoa real que veio habitar nos verdadeiros seguidores de Jesus Cristo depois que Jesus ressuscitou dos mortos e ascendeu ao c&eacute;u (Atos 2). No evangelho de <strong>Jo&atilde;o 14:16-18 </strong>Jesus fala aos Seus ap&oacute;stolos.</p>\r\n\r\n<p>O Esp&iacute;rito Santo n&atilde;o &eacute; uma sombra vaga e et&eacute;rea, nem uma for&ccedil;a ativa ou impessoal. Ele &eacute; uma pessoa igual em todos os sentidos ao Deus Pai e ao Deus Filho. Ele &eacute; considerado a terceira pessoa da Trindade. (Mateus 28:18-20)</p>\r\n\r\n<p>Deus &eacute; Pai, Filho e Esp&iacute;rito Santo. E todos os atributos divinos atribu&iacute;dos ao Pai e ao Filho s&atilde;o igualmente atribu&iacute;dos ao Esp&iacute;rito Santo. Quando uma pessoa nasce de novo, crendo e recebendo a Jesus Cristo (Jo&atilde;o 1:12,13; 3:3-21), Deus habita nessa pessoa por meio do Esp&iacute;rito Santo (1Cor&iacute;ntios 3:16). O Esp&iacute;rito Santo tem intelecto (1Cor&iacute;ntios 2:11), emo&ccedil;&atilde;o (Ef&eacute;sios 4:30) e vontade (1Cor&iacute;ntios 12:11).</p>\r\n\r\n<ol>\r\n	<li><strong>Papel do Espirito Santo</strong></li>\r\n</ol>\r\n\r\n<ul>\r\n	<li>&nbsp;Um dos pap&eacute;is principais do Esp&iacute;rito Santo &eacute; que Ele testemunha sobre Jesus Cristo (Jo&atilde;o 15:26;&nbsp; 16:14). Ele fala ao cora&ccedil;&atilde;o das pessoas sobre a verdade de Jesus Cristo.</li>\r\n	<li>O Esp&iacute;rito Santo tamb&eacute;m age como um mestre dos crist&atilde;os. Ele revela aos crist&atilde;os a vontade e a verdade de Deus. (Jo&atilde;o 14:26; Jo&atilde;o 16:13).</li>\r\n	<li>Molda nosso car&aacute;ter para que o car&aacute;ter de Deus seja produzido em nossas vidas. J&aacute; que n&atilde;o podemos fazer o que &eacute; certo por n&oacute;s mesmos, o Esp&iacute;rito Santo produzir&aacute; em nossas vidas amor, alegria, paz, paci&ecirc;ncia, bondade, benignidade, fidelidade, mansid&atilde;o e dom&iacute;nio pr&oacute;prio (Gal&aacute;tas 5:22,23). Ao inv&eacute;s de tentarmos ser am&aacute;veis, pacientes, bondosos, Deus nos chama a depender dEle para produzir essas qualidades em nossa vida. Assim, os crist&atilde;os s&atilde;o chamados a andar no Esp&iacute;rito (Gal&aacute;tas 5:25) e ser cheios do Esp&iacute;rito (Ef&eacute;sios 5:18).</li>\r\n	<li>Capacita os crist&atilde;os a realizarem os deveres ministeriais que promovem crescimento espiritual entre os crist&atilde;os (<em>Romanos 12</em>; <em>1 Cor&iacute;ntios 12</em>; Ef&eacute;sios 4).</li>\r\n</ul>\r\n\r\n<p>Agora PARE, PENSE E REFLITA: Quais dessas qualidades encontradas nas nove fatias do fruto do Esp&iacute;rito, voc&ecirc; gostaria que o Esp&iacute;rito Santo produzisse em voc&ecirc;?</p>\r\n\r\n<ul>\r\n	<li>O Esp&iacute;rito Santo tamb&eacute;m realiza uma fun&ccedil;&atilde;o para os n&atilde;o crist&atilde;os. Ele convence o cora&ccedil;&atilde;o das pessoas sobre a verdade de Deus, mostrando qu&atilde;o pecadores n&oacute;s somos &ndash; necessitados do perd&atilde;o de Deus; e qu&atilde;o justo Jesus &eacute; &ndash; Ele morreu em nosso lugar, por nossos pecados; e sobre o julgamento final de Deus, que julgar&aacute; o mundo e aqueles que n&atilde;o O conheceram (Jo&atilde;o 16:8-11). O Esp&iacute;rito Santo atua em nossos cora&ccedil;&otilde;es e mentes, nos chamando para nos arrependermos e nos voltarmos a Deus, para recebermos perd&atilde;o e uma nova vida.</li>\r\n</ul>\r\n\r\n<ol>\r\n	<li><strong>Conclus&atilde;o:</strong></li>\r\n</ol>\r\n\r\n<p>N&atilde;o h&aacute; como viver o cristianismo de forma eficaz sem a ajuda do Esp&iacute;rito Santo. &Eacute; ele quem torna a obedi&ecirc;ncia a Deus, algo poss&iacute;vel.</p>\r\n\r\n<p>Devemos pedir ao Senhor que nos revista, encha com o Esp&iacute;rito Santo, para que possamos trilhar os caminhos da justi&ccedil;a de Deus em Cristo.</p>\r\n\r\n<p>&nbsp;</p>', '(Próximo estudo coloco as referências)');

-- --------------------------------------------------------

--
-- Estrutura para tabela `eventos`
--

CREATE TABLE `eventos` (
  `Id` int(11) NOT NULL,
  `Evento` varchar(100) DEFAULT NULL,
  `Descriacao` text DEFAULT NULL,
  `DataInicio` date DEFAULT NULL,
  `DataTermino` date DEFAULT NULL,
  `Anotacoes` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `eventos`
--

INSERT INTO `eventos` (`Id`, `Evento`, `Descriacao`, `DataInicio`, `DataTermino`, `Anotacoes`) VALUES
(4, 'Tela Crente', '<p>Neste s&aacute;bado, na igreja, &agrave;s 15h. Convide uma pessoa. Tamb&eacute;m teremos telinha crente: nossos pequeninos precisam estar presentes!????????</p>', '2018-08-04', '2018-08-04', 'Convide um amigo ou amiga.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `financeiro`
--

CREATE TABLE `financeiro` (
  `Id` int(11) NOT NULL,
  `id_discipulo` int(11) DEFAULT NULL COMMENT 'para controle de dizimos',
  `Tipo` tinyint(1) DEFAULT NULL COMMENT 'Receita,Despesa',
  `Tipo_Recebimento` tinyint(1) NOT NULL,
  `Despesas` decimal(10,2) DEFAULT NULL,
  `Receitas` decimal(10,2) DEFAULT NULL,
  `Descricao` varchar(60) DEFAULT NULL,
  `Conta_Caixa` int(11) DEFAULT NULL,
  `N_Documento` varchar(20) DEFAULT NULL,
  `Dt_Lancamento` date DEFAULT NULL,
  `Vencimento` date DEFAULT NULL,
  `Centro_de_Custo` int(11) DEFAULT NULL COMMENT 'olg conta',
  `Situacao` tinyint(1) DEFAULT NULL,
  `FormaPagto` tinyint(3) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

--
-- Despejando dados para a tabela `financeiro`
--

INSERT INTO `financeiro` (`Id`, `id_discipulo`, `Tipo`, `Tipo_Recebimento`, `Despesas`, `Receitas`, `Descricao`, `Conta_Caixa`, `N_Documento`, `Dt_Lancamento`, `Vencimento`, `Centro_de_Custo`, `Situacao`, `FormaPagto`) VALUES
(45, NULL, 1, 2, NULL, '31.00', 'O FERETA CIRCUO DE ORAÇÃO', 8, NULL, '2018-01-25', '2018-01-25', 10, 3, 5),
(44, NULL, 1, 2, NULL, '51.00', 'Paula Fernanda (DIZIMOS)', 1, NULL, '2018-01-22', '2018-01-22', 14, 3, 5),
(43, NULL, 1, 2, NULL, '3.50', 'OFERTA DA CÉLULA FILADEFIA', 8, NULL, '2018-01-22', '2018-01-22', 8, 3, 5),
(42, NULL, 1, 2, NULL, '176.00', 'OFERTA DOMINICAL CAMPANHA', 8, NULL, '2018-01-21', '2018-01-21', 14, 3, 5),
(85, 86, 1, 0, NULL, '190.00', 'DIZIMO NÃO IDENTIFICADO', 1, NULL, '2018-07-17', '2018-02-25', 14, 3, 5);

-- --------------------------------------------------------

--
-- Estrutura para tabela `fin_centro_de_custo`
--

CREATE TABLE `fin_centro_de_custo` (
  `Id` int(11) NOT NULL,
  `Conta` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

--
-- Despejando dados para a tabela `fin_centro_de_custo`
--

INSERT INTO `fin_centro_de_custo` (`Id`, `Conta`) VALUES
(6, 'Pagamento Funcionarios'),
(7, 'Manutenção da Igreja'),
(8, 'Oferta/Celula'),
(10, 'Oferta/Igreja'),
(11, 'Agua Minieral'),
(12, 'Encontro com Deus'),
(13, 'Oferta/Circulo de Oração'),
(14, 'Dizimo/Membros'),
(15, 'Em Caixa 2017'),
(16, 'Ajuda pastoral'),
(17, 'Pagamento/Contabilidade'),
(18, 'Aluguel/Igreja'),
(19, 'Saida de caixa');

-- --------------------------------------------------------

--
-- Estrutura para tabela `fin_conta_caixa`
--

CREATE TABLE `fin_conta_caixa` (
  `Id` int(11) NOT NULL,
  `Tipo` int(11) DEFAULT NULL,
  `Conta_Caixa` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

--
-- Despejando dados para a tabela `fin_conta_caixa`
--

INSERT INTO `fin_conta_caixa` (`Id`, `Tipo`, `Conta_Caixa`) VALUES
(1, 1, 'Dizimo'),
(3, 0, 'Conta de Energia'),
(4, 0, 'Conta de Agua'),
(8, 1, 'Oferta'),
(9, 1, 'Boas Novas'),
(10, 0, 'Aluguel'),
(11, 0, 'Telefone/Celular'),
(12, 1, 'Outros - Infraestrutura'),
(13, 0, 'Zelador'),
(14, 1, 'Oferta Circulo de Oração'),
(15, 1, 'Oferta Chá do Amor'),
(16, 0, 'Ajuda Pastoral'),
(17, 0, 'Saída de Caixa'),
(18, 0, 'Pag. Contador'),
(19, 0, 'Pag. Contador');

-- --------------------------------------------------------

--
-- Estrutura para tabela `fin_forma_pgto`
--

CREATE TABLE `fin_forma_pgto` (
  `Id` int(11) NOT NULL,
  `filtro_tipo_recebimento` tinyint(3) DEFAULT NULL,
  `Forma_Pagto` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

--
-- Despejando dados para a tabela `fin_forma_pgto`
--

INSERT INTO `fin_forma_pgto` (`Id`, `filtro_tipo_recebimento`, `Forma_Pagto`) VALUES
(4, 1, 'Boleto'),
(5, 2, 'Dinheiro'),
(9, 1, 'Cartao de Debito'),
(10, 1, 'Cartao Credito');

-- --------------------------------------------------------

--
-- Estrutura para tabela `fin_situacao`
--

CREATE TABLE `fin_situacao` (
  `Id` int(11) NOT NULL,
  `id_tipo` tinyint(1) DEFAULT NULL,
  `Situacao` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

--
-- Despejando dados para a tabela `fin_situacao`
--

INSERT INTO `fin_situacao` (`Id`, `id_tipo`, `Situacao`) VALUES
(1, 0, '<div class=\"badge bg-cobalt\">Pago</div>'),
(2, 0, '<div class=\"badge bg-red\">A Pagar</div>'),
(3, 1, '<div class=\"badge bg-darker\">Recebido</div>'),
(4, 1, '<div class=\"badge bg-emerald\">A Receber</div>');

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionarios`
--

CREATE TABLE `funcionarios` (
  `Id` int(11) NOT NULL,
  `EhMembro` tinyint(1) DEFAULT NULL,
  `Data_Admissao` date DEFAULT NULL,
  `Nome` varchar(100) NOT NULL DEFAULT '',
  `Data_Nasc` date DEFAULT NULL,
  `Estado_Civil` enum('Solteiro','Casado','Viuvo','Divorciado','Amasiado') DEFAULT NULL,
  `Endereco` varchar(100) DEFAULT NULL,
  `Bairro` varchar(50) DEFAULT NULL,
  `Cidade` varchar(60) DEFAULT NULL,
  `UF` char(2) DEFAULT 'SE',
  `CEP` varchar(10) DEFAULT NULL,
  `Celular` varchar(20) DEFAULT NULL,
  `Telefone Fixo` varchar(50) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Cargo` varchar(100) DEFAULT NULL,
  `Setor` varchar(50) DEFAULT NULL,
  `CPF` varchar(11) DEFAULT NULL,
  `RG` varchar(25) DEFAULT NULL,
  `Org_Exp` varchar(20) DEFAULT NULL,
  `Data_Expedicao` date DEFAULT NULL,
  `CTPS_N` varchar(30) DEFAULT NULL,
  `CTPS_Serie` varchar(10) DEFAULT NULL,
  `Titulo_Eleitor` varchar(20) DEFAULT NULL,
  `Numero_Filhos` varchar(5) DEFAULT NULL,
  `Escolaridade` enum('Basico','Fundamental','Medio','Superior') DEFAULT NULL,
  `Situacao` enum('Completo','Incompleto','Cursando') DEFAULT NULL,
  `Qual_ano` varchar(5) DEFAULT NULL,
  `Observacoes` varchar(255) DEFAULT NULL,
  `Inativo` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Despejando dados para a tabela `funcionarios`
--

INSERT INTO `funcionarios` (`Id`, `EhMembro`, `Data_Admissao`, `Nome`, `Data_Nasc`, `Estado_Civil`, `Endereco`, `Bairro`, `Cidade`, `UF`, `CEP`, `Celular`, `Telefone Fixo`, `Email`, `Cargo`, `Setor`, `CPF`, `RG`, `Org_Exp`, `Data_Expedicao`, `CTPS_N`, `CTPS_Serie`, `Titulo_Eleitor`, `Numero_Filhos`, `Escolaridade`, `Situacao`, `Qual_ano`, `Observacoes`, `Inativo`) VALUES
(1, 0, '2012-12-12', 'fulana de tal', '1990-10-10', 'Solteiro', 'rua cc', 'setor vv', 'franca', 'SE', '74550420', '65662312', NULL, 'ghxg@uol.com.br', 'Secretaria', 'secretaria', '15632599-80', '254865', 'sspsp', '1999-12-12', '2154', '125', NULL, '0', 'Medio', 'Completo', '2', 'bla bla bla', NULL),
(2, 1, '2013-01-01', 'Monteiro Lobato', '1900-01-21', 'Casado', 'Rua x', 'x', 'x', 'GO', '74000000', NULL, NULL, NULL, NULL, NULL, '11111111111', '1111111', NULL, NULL, NULL, NULL, NULL, '7', 'Superior', 'Completo', '3', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcoes_exerce`
--

CREATE TABLE `funcoes_exerce` (
  `Id` int(11) NOT NULL,
  `Funcao` varchar(40) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Despejando dados para a tabela `funcoes_exerce`
--

INSERT INTO `funcoes_exerce` (`Id`, `Funcao`) VALUES
(1, 'Lider de Louvor'),
(2, 'Dirigente de Mocidade'),
(3, 'Dirigente do Circulo de Oracao'),
(4, 'Lider de Evangelismo'),
(5, 'Lider de Visitas'),
(6, 'Lider de C?lula'),
(7, 'Supervisor de Regiao'),
(8, 'Discipulado'),
(9, 'LOUVOR'),
(10, 'MJ'),
(11, 'Membro'),
(12, 'Pastor'),
(13, 'Obreira'),
(14, 'Jovem Menor'),
(15, 'COREOGRAFIA'),
(16, 'CANDIDATA'),
(17, '300 TROPA DE ELITE'),
(18, 'PASTORA'),
(19, 'LEVITA'),
(20, 'OBREIRO'),
(21, 'DJ IGREJA'),
(22, 'LIDER'),
(23, 'Membro(a)');

-- --------------------------------------------------------

--
-- Estrutura para tabela `igrejas`
--

CREATE TABLE `igrejas` (
  `Id_igreja` int(11) NOT NULL,
  `Igreja` varchar(50) DEFAULT NULL,
  `DirigenteResponsavel` varchar(100) DEFAULT NULL,
  `CNPJ` varchar(20) NOT NULL DEFAULT '',
  `Email` varchar(100) DEFAULT NULL,
  `Endereco` varchar(50) NOT NULL DEFAULT '',
  `Bairro` varchar(25) DEFAULT NULL,
  `Cidade` varchar(22) DEFAULT NULL,
  `UF` varchar(2) DEFAULT NULL,
  `CEP` varchar(9) DEFAULT NULL,
  `Telefone1` varchar(15) DEFAULT NULL,
  `Telefone2` varchar(15) DEFAULT NULL,
  `Fax` varchar(15) DEFAULT NULL,
  `Site_Igreja` varchar(100) DEFAULT NULL,
  `Email_da_igreja` varchar(100) DEFAULT NULL,
  `Modelo` tinyint(3) DEFAULT NULL,
  `Data_de_Fundacao` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

--
-- Despejando dados para a tabela `igrejas`
--

INSERT INTO `igrejas` (`Id_igreja`, `Igreja`, `DirigenteResponsavel`, `CNPJ`, `Email`, `Endereco`, `Bairro`, `Cidade`, `UF`, `CEP`, `Telefone1`, `Telefone2`, `Fax`, `Site_Igreja`, `Email_da_igreja`, `Modelo`, `Data_de_Fundacao`) VALUES
(22, 'IGREJA BATISTA SEMEANDO CURA', 'LUIS PEREIRA GOUVEIA', '12.584.913/0001-81', 'ticginformatica@gmail.com', 'RUA JOSÉ ARAÚJO NUNES 40', 'SÃO JORGE', 'ARACAJU', 'SE', '49043000', '79999442001', '79989753597', '79988553597', 'https://www.facebook.com/ibscura/', 'ibsc2015@hotmail.com', 11, '2010-07-17');

-- --------------------------------------------------------

--
-- Estrutura para tabela `lista_videos`
--

CREATE TABLE `lista_videos` (
  `Id_video` int(11) NOT NULL,
  `TituloVideo` varchar(100) DEFAULT NULL,
  `Embed_do_Video` varchar(100) DEFAULT NULL,
  `Anotacoes` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `lista_videos`
--

INSERT INTO `lista_videos` (`Id_video`, `TituloVideo`, `Embed_do_Video`, `Anotacoes`) VALUES
(1, 'Os livros de Salom?o', '//www.youtube.com/embed/QjSAhbVkbJU', 'Lorem Ipsum ? simplesmente uma simula??o de texto da ind?stria tipogr?fica e de impressos, e vem sendo utilizado desde o s?culo XVI, quando um impressor desconhecido pegou uma bandeja de tipos e os embaralhou para fazer um livro de modelos de tipos. Lorem Ipsum sobreviveu n?o s? a cinco s?culos, como tamb?m ao salto para a editora??o eletr?nica, permanecendo essencialmente inalterado. Se popularizou na d?cada de 60, quando a Letraset lan?ou decalques contendo passagens de Lorem Ipsum, e mais recentemente quando passou a ser integrado a softwares de editora??o eletr?nica como Aldus PageMaker.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `membro`
--

CREATE TABLE `membro` (
  `Id_membro` int(11) NOT NULL,
  `Da_Igreja` int(11) DEFAULT NULL COMMENT 'Igreja que pertence',
  `Matricula` varchar(20) DEFAULT NULL,
  `CargoMinisterial` tinyint(3) DEFAULT NULL,
  `Nome` varchar(60) DEFAULT NULL,
  `Sexo` enum('Masculino','Feminino') DEFAULT 'Masculino',
  `DataNasc` date DEFAULT NULL,
  `Nacionalidade` varchar(30) DEFAULT 'Brasileiro',
  `EstadoCivil` enum('Solteiro(a)','Casado(a)','Divorciado(a)','Viuvo(a)','Amasiado(a)') DEFAULT 'Solteiro(a)',
  `CPF` varchar(15) DEFAULT NULL,
  `RG` varchar(15) DEFAULT NULL,
  `Profissao` varchar(60) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `TelefoneRes` char(15) DEFAULT NULL,
  `Celular_1` char(15) DEFAULT NULL,
  `Celular_2` char(15) DEFAULT NULL,
  `GrauEscolaridade` tinyint(3) DEFAULT NULL,
  `Curso` varchar(50) DEFAULT NULL,
  `Endereco` varchar(60) DEFAULT NULL,
  `Complemento` varchar(50) DEFAULT NULL,
  `Bairro` varchar(30) DEFAULT NULL,
  `Cidade` varchar(30) DEFAULT NULL,
  `UF` varchar(2) DEFAULT NULL,
  `CEP` char(9) DEFAULT NULL,
  `Anotacoes` text DEFAULT NULL,
  `Foto` varchar(50) DEFAULT NULL,
  `Conjuge` varchar(80) DEFAULT NULL,
  `N_Filhos` varchar(5) DEFAULT NULL,
  `Empresa_trabalha` varchar(60) DEFAULT NULL,
  `Fone_Empresa` varchar(15) DEFAULT NULL,
  `Celula` int(11) DEFAULT NULL COMMENT 'Faz parte da Celula',
  `Nome_da_Familia` varchar(60) DEFAULT NULL,
  `Nome_da_Mae` varchar(60) DEFAULT NULL,
  `Nome_do_Pai` varchar(60) DEFAULT NULL,
  `Situacao` tinyint(3) DEFAULT NULL,
  `Data_batismo` date DEFAULT NULL,
  `Admissao` date DEFAULT NULL,
  `Tipo_Admissao` tinyint(3) DEFAULT NULL,
  `Funcao` tinyint(3) DEFAULT NULL,
  `Rede_Ministerial` tinyint(3) DEFAULT NULL,
  `Data_Casamento` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `membro`
--

INSERT INTO `membro` (`Id_membro`, `Da_Igreja`, `Matricula`, `CargoMinisterial`, `Nome`, `Sexo`, `DataNasc`, `Nacionalidade`, `EstadoCivil`, `CPF`, `RG`, `Profissao`, `Email`, `TelefoneRes`, `Celular_1`, `Celular_2`, `GrauEscolaridade`, `Curso`, `Endereco`, `Complemento`, `Bairro`, `Cidade`, `UF`, `CEP`, `Anotacoes`, `Foto`, `Conjuge`, `N_Filhos`, `Empresa_trabalha`, `Fone_Empresa`, `Celula`, `Nome_da_Familia`, `Nome_da_Mae`, `Nome_do_Pai`, `Situacao`, `Data_batismo`, `Admissao`, `Tipo_Admissao`, `Funcao`, `Rede_Ministerial`, `Data_Casamento`) VALUES
(66, 22, '0001', 13, 'Luis Pereira ', 'Masculino', '1980-12-27', 'Brasileiro', 'Casado(a)', '0079787569', '30144758', 'Programador', 'ibsc2015@hotmail.com', '79988553597', '7999941-2131', '5564564654', 4, NULL, 'Rua F8 nº. 85', ' Dantas', 'SÃO JUDAS', 'Aracaju', 'SE', '49040000', NULL, 'foto66.png', ' Fernanda  de Carvalho ', '2', 'Igreja Batista ', NULL, NULL, 'Luis Gouveia', 'Eunilde  Gomes ', 'Pereira Gouveia', 2, '1993-12-19', '2010-07-17', 3, 12, 10, '2010-07-17');
-- --------------------------------------------------------

--
-- Estrutura para tabela `meses`
--

CREATE TABLE `meses` (
  `id` int(11) NOT NULL,
  `Mes` varchar(10) DEFAULT NULL,
  `Mes_abrev` char(3) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Despejando dados para a tabela `meses`
--

INSERT INTO `meses` (`id`, `Mes`, `Mes_abrev`) VALUES
(1, 'Janeiro', 'JAN'),
(2, 'Fevereiro', 'FEV'),
(3, 'Mar?o', 'MAR'),
(4, 'Abril', 'ABR'),
(5, 'Maio', 'MAI'),
(6, 'Junho', 'JUN'),
(7, 'Julho', 'JUL'),
(8, 'Agosto', 'AGO'),
(9, 'Setembro', 'SET'),
(10, 'Outubro', 'OUT'),
(11, 'Novembro', 'NOV'),
(12, 'Dezembro', 'DEZ');

-- --------------------------------------------------------

--
-- Estrutura para tabela `modelo_igreja`
--

CREATE TABLE `modelo_igreja` (
  `Id` tinyint(3) NOT NULL,
  `Modelo` varchar(60) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `modelo_igreja`
--

INSERT INTO `modelo_igreja` (`Id`, `Modelo`) VALUES
(11, 'Igreja em Celula'),
(22, 'Pequeno Grupo'),
(33, 'Renovada'),
(34, 'Tradicional'),
(35, 'Congregacao'),
(36, 'Idependente');

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `ofertasporcriterio`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `ofertasporcriterio` (
`Mes` int(2)
,`Tipo` tinyint(1)
,`Conta_Caixa` int(11)
,`Situacao` tinyint(1)
,`Descricao` varchar(60)
,`Receitas` decimal(10,2)
,`FormaPagto` tinyint(3)
,`Dt_Lancamento` date
,`Vencimento` date
,`Centro_de_Custo` int(11)
,`id_discipulo` int(11)
,`Id` int(11)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `ofertassmesatual`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `ofertassmesatual` (
`Tipo` tinyint(1)
,`Conta_Caixa` int(11)
,`Situacao` tinyint(1)
,`Descricao` varchar(60)
,`Receitas` decimal(10,2)
,`FormaPagto` tinyint(3)
,`Dt_Lancamento` date
,`Vencimento` date
,`Centro_de_Custo` int(11)
,`id_discipulo` int(11)
,`Id` int(11)
);

-- --------------------------------------------------------

--
-- Estrutura para tabela `plano_oracao`
--

CREATE TABLE `plano_oracao` (
  `Id_ora` int(11) NOT NULL,
  `Motivo_da_Oracao` varchar(100) DEFAULT NULL,
  `Anotacoes` varchar(255) DEFAULT '',
  `Prioridade` tinyint(1) DEFAULT NULL,
  `Plano_p_todos` tinyint(1) DEFAULT NULL COMMENT 'Plano de Oracao para todos os membros da Igreja',
  `Oracao_feita` tinyint(1) DEFAULT NULL COMMENT 'Oracao feita / Concluida',
  `Data_do_Cadastro` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `plano_oracao`
--

INSERT INTO `plano_oracao` (`Id_ora`, `Motivo_da_Oracao`, `Anotacoes`, `Prioridade`, `Plano_p_todos`, `Oracao_feita`, `Data_do_Cadastro`) VALUES
(2, 'Orar pelos membros IBSC', 'Tirar um  tempo de oração todos os dias, pelos membros ativos da igreja Batista Semeando Cura', 2, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `prioridade`
--

CREATE TABLE `prioridade` (
  `Id_prior` int(11) NOT NULL,
  `Prioridade` varchar(90) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `prioridade`
--

INSERT INTO `prioridade` (`Id_prior`, `Prioridade`) VALUES
(1, '<span class=\"label bg-magenta\"><i class=\"icon-arrow-up icon-white\"></i>Urgente</span>'),
(2, '<span class=\"label bg-red\"><i class=\"icon-arrow-up icon-white\"></i> Alta</span>'),
(3, '<span class=\"label bg-orange\"><i class=\"icon-flag icon-white\"></i> Media</span>'),
(4, '<span class=\"label bg-green\"><i class=\"icon-arrow-down icon-white\"></i> Baixa</span>');

-- --------------------------------------------------------

--
-- Estrutura para tabela `rede_ministerial`
--

CREATE TABLE `rede_ministerial` (
  `Id` int(11) NOT NULL,
  `Rede_Ministerial` varchar(40) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Despejando dados para a tabela `rede_ministerial`
--

INSERT INTO `rede_ministerial` (`Id`, `Rede_Ministerial`) VALUES
(1, 'Rede de Homens'),
(2, 'Rede de Mulheres'),
(3, 'Rede de Jovens'),
(4, 'Rede de Criancas'),
(5, 'Rede Ministerial de Oracao'),
(6, 'Regiao Verde'),
(7, 'Regiao Azul'),
(8, 'Regiao Amarela'),
(9, 'Regiao Vermelha'),
(10, 'SEDE NACIONAL'),
(11, 'FORMOSA CEPRODEUS');

-- --------------------------------------------------------

--
-- Estrutura para tabela `situacao_membro`
--

CREATE TABLE `situacao_membro` (
  `Id` int(11) NOT NULL,
  `Situacao` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `situacao_membro`
--

INSERT INTO `situacao_membro` (`Id`, `Situacao`) VALUES
(1, 'Disciplinado'),
(2, 'Ativo'),
(3, 'Inativo'),
(4, 'Falecido'),
(5, 'Pedio a Carta');

-- --------------------------------------------------------

--
-- Estrutura para tabela `smtp`
--

CREATE TABLE `smtp` (
  `Id` int(11) NOT NULL,
  `SMTP` varchar(40) DEFAULT NULL,
  `SMTP_Porta` varchar(10) DEFAULT NULL,
  `SMTP_Usuario` varchar(60) DEFAULT NULL,
  `SMTP_Senha` varchar(50) DEFAULT NULL,
  `Email_de_Envio` varchar(65) DEFAULT NULL,
  `Email_de_Recebimento` varchar(65) DEFAULT NULL,
  `Seguranca` enum('SSL','TLS') DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `smtp`
--

INSERT INTO `smtp` (`Id`, `SMTP`, `SMTP_Porta`, `SMTP_Usuario`, `SMTP_Senha`, `Email_de_Envio`, `Email_de_Recebimento`, `Seguranca`) VALUES
(1, 'smtp.gmail.com', '467', 'ekkllesias', 'gfrkiugtr', 'ekkllesias@gmail.com', 'ekkllesias@gmail.com', 'SSL');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipo_admissao`
--

CREATE TABLE `tipo_admissao` (
  `Id` int(11) NOT NULL,
  `Tipo_Admissao` varchar(40) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Despejando dados para a tabela `tipo_admissao`
--

INSERT INTO `tipo_admissao` (`Id`, `Tipo_Admissao`) VALUES
(1, 'Batismo'),
(2, 'Recomenda??o'),
(3, 'Aclama??o'),
(4, 'Carta'),
(5, 'Outros'),
(6, 'Transferencia');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ufs`
--

CREATE TABLE `ufs` (
  `UniFedSigla` varchar(2) NOT NULL,
  `UniFedNome` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

--
-- Despejando dados para a tabela `ufs`
--

INSERT INTO `ufs` (`UniFedSigla`, `UniFedNome`) VALUES
('AC', 'Acre                          '),
('AL', 'Alagoas                       '),
('AM', 'Amazonas                      '),
('AP', 'Amapá                        '),
('BA', 'Bahia                         '),
('CE', 'Ceará                        '),
('DF', 'Distrito Federal              '),
('ES', 'Espírito Santo               '),
('GO', 'Goiás                        '),
('MA', 'Maranhão                     '),
('MG', 'Minas Gerais                  '),
('MS', 'Mato Grosso do Sul            '),
('MT', 'Mato Grosso                   '),
('PA', 'Pará                         '),
('PB', 'Paraíba                      '),
('PE', 'Pernambuco                    '),
('PI', 'Piauí                        '),
('PR', 'Paraná                       '),
('RJ', 'Rio de Janeiro                '),
('RN', 'Rio Grande do Norte           '),
('RO', 'Rondônia                     '),
('RR', 'Roraima                       '),
('RS', 'Rio Grande do Sul             '),
('SC', 'Santa Catarina                '),
('SE', 'Sergipe                       '),
('SP', 'São Paulo'),
('TO', 'Tocantins                     ');

-- --------------------------------------------------------

--
-- Estrutura para tabela `userlevelpermissions`
--

CREATE TABLE `userlevelpermissions` (
  `userlevelid` int(11) NOT NULL,
  `tablename` varchar(255) NOT NULL,
  `permission` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `userlevelpermissions`
--

INSERT INTO `userlevelpermissions` (`userlevelid`, `tablename`, `permission`) VALUES
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}agenda', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}Agenda_Morta', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}ajuda', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}Aniversariantes', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}audittrail', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}bancos', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}bens_patrimoniais', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}caixadodia', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}cargoscelulas', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}cargosministeriais', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}cartas', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}celulas', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}configuracoes', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}contatos', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}conta_bancaria', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}controle_tarefas', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}dizimos', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}dizimosmesatual', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}dizimosporcriterio', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}Emailsporfuncao', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}escolaridade', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}estudos_biblicos', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}EtiquetasMalaDireta', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}eventos', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}financeiro', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}fin_centro_de_custo', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}fin_conta_caixa', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}fin_forma_pgto', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}fin_situacao', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}funcionarios', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}funcoes_exerce', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}igrejas', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}ListadeEmailporCargo', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}ListaEmailsporFuncao', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}Lista_de_Emails', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}lista_videos', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}membro', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}meus_lembretes', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}modelo_igreja', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}ofertasporcriterio', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}ofertassmesatual', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}plano_oracao', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}print_cartaexclusao', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}print_cartarecomendacao', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}print_transferencia', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}rede_ministerial', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}RelatorioAdmissao', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}RelatorioCasamento', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}RelatoriodataBatismo', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}situacao_membro', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}smtp', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}tipo_admissao', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}userlevels', 45),
(0, '{2B7992FC-5911-46A7-9310-01F4D4225C49}usuarios', 45),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}agenda', 111),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}Agenda_Morta', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}ajuda', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}Aniversariantes', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}audittrail', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}bancos', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}bens_patrimoniais', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}caixadodia', 105),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}cargoscelulas', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}cargosministeriais', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}cartas', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}celulas', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}configuracoes', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}conta_bancaria', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}contatos', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}controle_tarefas', 109),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}dizimos', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}dizimosmesatual', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}dizimosporcriterio', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}Emailsporfuncao', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}escolaridade', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}estudos_biblicos', 109),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}EtiquetasMalaDireta', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}eventos', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}fin_centro_de_custo', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}fin_conta_caixa', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}fin_forma_pgto', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}fin_situacao', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}financeiro', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}funcionarios', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}funcoes_exerce', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}igrejas', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}Lista_de_Emails', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}lista_videos', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}ListadeEmailporCargo', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}ListaEmailsporFuncao', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}membro', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}meus_lembretes', 111),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}modelo_igreja', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}ofertasporcriterio', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}ofertassmesatual', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}plano_oracao', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}print_cartaexclusao', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}print_cartarecomendacao', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}print_transferencia', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}rede_ministerial', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}RelatorioAdmissao', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}RelatorioCasamento', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}situacao_membro', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}smtp', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}tipo_admissao', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}userlevels', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}usuarios', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}RelatoriodataBatismo', 0),
(4, '{2B7992FC-5911-46A7-9310-01F4D4225C49}print_cartaoficio', 0),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}agenda', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}Agenda_Morta', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}ajuda', 0),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}Aniversariantes', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}audittrail', 0),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}bancos', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}bens_patrimoniais', 0),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}caixadodia', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}cargoscelulas', 0),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}cargosministeriais', 0),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}cartas', 0),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}celulas', 0),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}configuracoes', 0),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}conta_bancaria', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}contatos', 0),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}controle_tarefas', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}dizimos', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}dizimosmesatual', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}dizimosporcriterio', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}Emailsporfuncao', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}escolaridade', 0),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}estudos_biblicos', 0),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}EtiquetasMalaDireta', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}eventos', 0),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}fin_centro_de_custo', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}fin_conta_caixa', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}fin_forma_pgto', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}fin_situacao', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}financeiro', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}funcionarios', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}funcoes_exerce', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}igrejas', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}Lista_de_Emails', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}lista_videos', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}ListadeEmailporCargo', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}ListaEmailsporFuncao', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}membro', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}meus_lembretes', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}modelo_igreja', 0),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}ofertasporcriterio', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}ofertassmesatual', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}plano_oracao', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}print_cartaexclusao', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}print_cartarecomendacao', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}print_transferencia', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}rede_ministerial', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}RelatorioAdmissao', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}RelatorioCasamento', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}situacao_membro', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}smtp', 0),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}tipo_admissao', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}userlevels', 0),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}usuarios', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}RelatoriodataBatismo', 109),
(1, '{2B7992FC-5911-46A7-9310-01F4D4225C49}print_cartaoficio', 109),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}agenda', 39),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}Agenda_Morta', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}ajuda', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}Aniversariantes', 39),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}audittrail', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}bancos', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}bens_patrimoniais', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}caixadodia', 39),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}cargoscelulas', 39),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}cargosministeriais', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}cartas', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}celulas', 39),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}configuracoes', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}conta_bancaria', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}contatos', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}controle_tarefas', 39),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}dizimos', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}dizimosmesatual', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}dizimosporcriterio', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}Emailsporfuncao', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}escolaridade', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}estudos_biblicos', 103),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}EtiquetasMalaDireta', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}eventos', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}fin_centro_de_custo', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}fin_conta_caixa', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}fin_forma_pgto', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}fin_situacao', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}financeiro', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}funcionarios', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}funcoes_exerce', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}igrejas', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}Lista_de_Emails', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}lista_videos', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}ListadeEmailporCargo', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}ListaEmailsporFuncao', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}membro', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}meus_lembretes', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}modelo_igreja', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}ofertasporcriterio', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}ofertassmesatual', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}plano_oracao', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}print_cartaexclusao', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}print_cartarecomendacao', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}print_transferencia', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}rede_ministerial', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}RelatorioAdmissao', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}RelatorioCasamento', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}situacao_membro', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}smtp', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}tipo_admissao', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}userlevels', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}usuarios', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}RelatoriodataBatismo', 0),
(5, '{2B7992FC-5911-46A7-9310-01F4D4225C49}print_cartaoficio', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}agenda', 105),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}Agenda_Morta', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}ajuda', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}Aniversariantes', 105),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}audittrail', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}bancos', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}bens_patrimoniais', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}caixadodia', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}cargoscelulas', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}cargosministeriais', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}cartas', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}celulas', 105),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}configuracoes', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}conta_bancaria', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}contatos', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}controle_tarefas', 105),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}dizimos', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}dizimosmesatual', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}dizimosporcriterio', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}Emailsporfuncao', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}escolaridade', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}estudos_biblicos', 105),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}EtiquetasMalaDireta', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}eventos', 105),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}fin_centro_de_custo', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}fin_conta_caixa', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}fin_forma_pgto', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}fin_situacao', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}financeiro', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}funcionarios', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}funcoes_exerce', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}igrejas', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}Lista_de_Emails', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}lista_videos', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}ListadeEmailporCargo', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}ListaEmailsporFuncao', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}membro', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}meus_lembretes', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}modelo_igreja', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}ofertasporcriterio', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}ofertassmesatual', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}plano_oracao', 104),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}print_cartaexclusao', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}print_cartarecomendacao', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}print_transferencia', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}rede_ministerial', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}RelatorioAdmissao', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}RelatorioCasamento', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}situacao_membro', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}smtp', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}tipo_admissao', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}userlevels', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}usuarios', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}RelatoriodataBatismo', 0),
(6, '{2B7992FC-5911-46A7-9310-01F4D4225C49}print_cartaoficio', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `userlevels`
--

CREATE TABLE `userlevels` (
  `userlevelid` int(11) NOT NULL,
  `userlevelname` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `userlevels`
--

INSERT INTO `userlevels` (`userlevelid`, `userlevelname`) VALUES
(-1, 'Administrador'),
(0, 'Default'),
(1, 'Tesoureiro'),
(3, 'Secretario'),
(4, 'LiderCelula'),
(5, 'SupervisorCelula'),
(6, 'Membros');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `u` int(2) NOT NULL,
  `Nome` varchar(35) DEFAULT NULL,
  `login` varchar(12) DEFAULT NULL,
  `senha` varchar(32) DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `Level` int(1) DEFAULT NULL,
  `profile` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`u`, `Nome`, `login`, `senha`, `email`, `Level`, `profile`) VALUES
(44253575, 'André Gouveia(Programador)', 'admin', '6a37f8c47d1597e48b7401a829a2e8d0', 'ticginformatica@gmail.com', -1, NULL),
(44253582, 'Usuário Membros', 'membro', '827ccb0eea8a706c4c34a16891f84e7b', 'membro@gmail.com', 6, NULL);

-- --------------------------------------------------------

--
-- Estrutura para view `anos`
--
DROP TABLE IF EXISTS `anos`;

CREATE SQL SECURITY DEFINER VIEW `anos`  AS  select distinct year(`financeiro`.`Dt_Lancamento`) AS `anos_financ` from `financeiro` ;

-- --------------------------------------------------------

--
-- Estrutura para view `caixadodia`
--
DROP TABLE IF EXISTS `caixadodia`;

CREATE SQL SECURITY DEFINER VIEW `caixadodia`  AS  select `financeiro`.`Tipo` AS `Tipo`,`financeiro`.`N_Documento` AS `N_Documento`,`financeiro`.`Conta_Caixa` AS `Conta_Caixa`,`financeiro`.`Situacao` AS `Situacao`,`financeiro`.`Descricao` AS `Descricao`,`financeiro`.`Receitas` AS `Receitas`,`financeiro`.`Despesas` AS `Despesas`,`financeiro`.`FormaPagto` AS `FormaPagto`,`financeiro`.`Dt_Lancamento` AS `Dt_Lancamento`,`financeiro`.`Vencimento` AS `Vencimento`,`financeiro`.`Centro_de_Custo` AS `Centro_de_Custo`,`financeiro`.`id_discipulo` AS `id_discipulo`,`financeiro`.`Id` AS `Id` from `financeiro` where `financeiro`.`Dt_Lancamento` = curdate() order by `financeiro`.`Dt_Lancamento` WITH LOCAL CHECK OPTION ;

-- --------------------------------------------------------

--
-- Estrutura para view `dizimos`
--
DROP TABLE IF EXISTS `dizimos`;

CREATE SQL SECURITY DEFINER VIEW `dizimos`  AS  select `financeiro`.`Tipo` AS `Tipo`,`financeiro`.`Conta_Caixa` AS `Conta_Caixa`,`financeiro`.`Situacao` AS `Situacao`,`financeiro`.`Descricao` AS `Descricao`,`financeiro`.`Receitas` AS `Receitas`,`financeiro`.`FormaPagto` AS `FormaPagto`,`financeiro`.`Dt_Lancamento` AS `Dt_Lancamento`,`financeiro`.`Vencimento` AS `Vencimento`,`financeiro`.`Centro_de_Custo` AS `Centro_de_Custo`,`financeiro`.`id_discipulo` AS `id_discipulo`,`financeiro`.`Id` AS `Id` from `financeiro` where `financeiro`.`Conta_Caixa` = 1 WITH LOCAL CHECK OPTION ;

-- --------------------------------------------------------

--
-- Estrutura para view `dizimosmesatual`
--
DROP TABLE IF EXISTS `dizimosmesatual`;

CREATE SQL SECURITY DEFINER VIEW `dizimosmesatual`  AS  select `financeiro`.`Tipo` AS `Tipo`,`financeiro`.`Conta_Caixa` AS `Conta_Caixa`,`financeiro`.`Situacao` AS `Situacao`,`financeiro`.`Descricao` AS `Descricao`,`financeiro`.`Receitas` AS `Receitas`,`financeiro`.`FormaPagto` AS `FormaPagto`,`financeiro`.`Dt_Lancamento` AS `Dt_Lancamento`,`financeiro`.`Vencimento` AS `Vencimento`,`financeiro`.`Centro_de_Custo` AS `Centro_de_Custo`,`financeiro`.`id_discipulo` AS `id_discipulo`,`financeiro`.`Id` AS `Id` from `financeiro` where `financeiro`.`Conta_Caixa` = 1 and month(`financeiro`.`Dt_Lancamento`) = month(curdate()) order by `financeiro`.`Dt_Lancamento` WITH LOCAL CHECK OPTION ;

-- --------------------------------------------------------

--
-- Estrutura para view `dizimosporcriterio`
--
DROP TABLE IF EXISTS `dizimosporcriterio`;

CREATE SQL SECURITY DEFINER VIEW `dizimosporcriterio`  AS  select month(`financeiro`.`Dt_Lancamento`) AS `Mes`,`financeiro`.`Tipo` AS `Tipo`,`financeiro`.`Conta_Caixa` AS `Conta_Caixa`,`financeiro`.`Situacao` AS `Situacao`,`financeiro`.`Descricao` AS `Descricao`,`financeiro`.`Receitas` AS `Receitas`,`financeiro`.`FormaPagto` AS `FormaPagto`,`financeiro`.`Dt_Lancamento` AS `Dt_Lancamento`,`financeiro`.`Vencimento` AS `Vencimento`,`financeiro`.`Centro_de_Custo` AS `Centro_de_Custo`,`financeiro`.`id_discipulo` AS `id_discipulo`,`financeiro`.`Id` AS `Id` from `financeiro` where `financeiro`.`Conta_Caixa` = 1 order by `financeiro`.`Dt_Lancamento` WITH LOCAL CHECK OPTION ;

-- --------------------------------------------------------

--
-- Estrutura para view `ofertasporcriterio`
--
DROP TABLE IF EXISTS `ofertasporcriterio`;

CREATE SQL SECURITY DEFINER VIEW `ofertasporcriterio`  AS  select month(`financeiro`.`Dt_Lancamento`) AS `Mes`,`financeiro`.`Tipo` AS `Tipo`,`financeiro`.`Conta_Caixa` AS `Conta_Caixa`,`financeiro`.`Situacao` AS `Situacao`,`financeiro`.`Descricao` AS `Descricao`,`financeiro`.`Receitas` AS `Receitas`,`financeiro`.`FormaPagto` AS `FormaPagto`,`financeiro`.`Dt_Lancamento` AS `Dt_Lancamento`,`financeiro`.`Vencimento` AS `Vencimento`,`financeiro`.`Centro_de_Custo` AS `Centro_de_Custo`,`financeiro`.`id_discipulo` AS `id_discipulo`,`financeiro`.`Id` AS `Id` from `financeiro` where `financeiro`.`Conta_Caixa` = 8 order by `financeiro`.`Dt_Lancamento` WITH LOCAL CHECK OPTION ;

-- --------------------------------------------------------

--
-- Estrutura para view `ofertassmesatual`
--
DROP TABLE IF EXISTS `ofertassmesatual`;

CREATE SQL SECURITY DEFINER VIEW `ofertassmesatual`  AS  select `financeiro`.`Tipo` AS `Tipo`,`financeiro`.`Conta_Caixa` AS `Conta_Caixa`,`financeiro`.`Situacao` AS `Situacao`,`financeiro`.`Descricao` AS `Descricao`,`financeiro`.`Receitas` AS `Receitas`,`financeiro`.`FormaPagto` AS `FormaPagto`,`financeiro`.`Dt_Lancamento` AS `Dt_Lancamento`,`financeiro`.`Vencimento` AS `Vencimento`,`financeiro`.`Centro_de_Custo` AS `Centro_de_Custo`,`financeiro`.`id_discipulo` AS `id_discipulo`,`financeiro`.`Id` AS `Id` from `financeiro` where `financeiro`.`Conta_Caixa` = 8 and month(`financeiro`.`Dt_Lancamento`) = month(curdate()) order by `financeiro`.`Dt_Lancamento` WITH LOCAL CHECK OPTION ;

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `agenda`
--
ALTER TABLE `agenda`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Data` (`Data`);

--
-- Índices de tabela `agsolucao`
--
ALTER TABLE `agsolucao`
  ADD PRIMARY KEY (`Id`);

--
-- Índices de tabela `ajuda`
--
ALTER TABLE `ajuda`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Pagina` (`pg`);

--
-- Índices de tabela `audittrail`
--
ALTER TABLE `audittrail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `datetime` (`datetime`),
  ADD KEY `script` (`script`),
  ADD KEY `user` (`user`),
  ADD KEY `action` (`action`),
  ADD KEY `table` (`table`),
  ADD KEY `field` (`field`),
  ADD KEY `keyvalue` (`keyvalue`(10)),
  ADD KEY `oldvalue` (`oldvalue`(10)),
  ADD KEY `newvalue` (`newvalue`(10));

--
-- Índices de tabela `bancos`
--
ALTER TABLE `bancos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `bens_patrimoniais`
--
ALTER TABLE `bens_patrimoniais`
  ADD PRIMARY KEY (`Id_Patri`),
  ADD KEY `Tipo` (`Tipo`),
  ADD KEY `Situacao` (`Situacao`);

--
-- Índices de tabela `cargoscelulas`
--
ALTER TABLE `cargoscelulas`
  ADD PRIMARY KEY (`id_cgcelula`);

--
-- Índices de tabela `cargosministeriais`
--
ALTER TABLE `cargosministeriais`
  ADD PRIMARY KEY (`id_cgm`);

--
-- Índices de tabela `cartas`
--
ALTER TABLE `cartas`
  ADD PRIMARY KEY (`Id`);

--
-- Índices de tabela `celulas`
--
ALTER TABLE `celulas`
  ADD PRIMARY KEY (`Id_celula`);

--
-- Índices de tabela `contatos`
--
ALTER TABLE `contatos`
  ADD PRIMARY KEY (`Id`);

--
-- Índices de tabela `conta_bancaria`
--
ALTER TABLE `conta_bancaria`
  ADD PRIMARY KEY (`Id`);

--
-- Índices de tabela `controle_tarefas`
--
ALTER TABLE `controle_tarefas`
  ADD PRIMARY KEY (`Id_tarefas`);

--
-- Índices de tabela `dias_semana`
--
ALTER TABLE `dias_semana`
  ADD PRIMARY KEY (`Id_semana`);

--
-- Índices de tabela `escolaridade`
--
ALTER TABLE `escolaridade`
  ADD PRIMARY KEY (`Id`);

--
-- Índices de tabela `estado_patrimonio`
--
ALTER TABLE `estado_patrimonio`
  ADD PRIMARY KEY (`Id_est_patri`);

--
-- Índices de tabela `estudos_biblicos`
--
ALTER TABLE `estudos_biblicos`
  ADD PRIMARY KEY (`Id_estu_bb`);

--
-- Índices de tabela `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`Id`);

--
-- Índices de tabela `financeiro`
--
ALTER TABLE `financeiro`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `situacao` (`Situacao`),
  ADD KEY `id_discipulo` (`id_discipulo`),
  ADD KEY `Dt_Lancamento` (`Dt_Lancamento`),
  ADD KEY `Vencimento` (`Vencimento`),
  ADD KEY `Conta_Caixa` (`Conta_Caixa`);

--
-- Índices de tabela `fin_centro_de_custo`
--
ALTER TABLE `fin_centro_de_custo`
  ADD PRIMARY KEY (`Id`);

--
-- Índices de tabela `fin_conta_caixa`
--
ALTER TABLE `fin_conta_caixa`
  ADD PRIMARY KEY (`Id`);

--
-- Índices de tabela `fin_forma_pgto`
--
ALTER TABLE `fin_forma_pgto`
  ADD PRIMARY KEY (`Id`);

--
-- Índices de tabela `fin_situacao`
--
ALTER TABLE `fin_situacao`
  ADD PRIMARY KEY (`Id`);

--
-- Índices de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD PRIMARY KEY (`Id`);

--
-- Índices de tabela `funcoes_exerce`
--
ALTER TABLE `funcoes_exerce`
  ADD PRIMARY KEY (`Id`);

--
-- Índices de tabela `igrejas`
--
ALTER TABLE `igrejas`
  ADD PRIMARY KEY (`Id_igreja`);

--
-- Índices de tabela `lista_videos`
--
ALTER TABLE `lista_videos`
  ADD PRIMARY KEY (`Id_video`);

--
-- Índices de tabela `membro`
--
ALTER TABLE `membro`
  ADD PRIMARY KEY (`Id_membro`),
  ADD KEY `Da_Igreja` (`Da_Igreja`),
  ADD KEY `DataNasc` (`DataNasc`),
  ADD KEY `CargoMinisterial` (`CargoMinisterial`),
  ADD KEY `Funcao` (`Funcao`),
  ADD KEY `Admissao` (`Admissao`),
  ADD KEY `Tipo_Admissao` (`Tipo_Admissao`),
  ADD KEY `EstadoCivil` (`EstadoCivil`);

--
-- Índices de tabela `meses`
--
ALTER TABLE `meses`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `modelo_igreja`
--
ALTER TABLE `modelo_igreja`
  ADD PRIMARY KEY (`Id`);

--
-- Índices de tabela `plano_oracao`
--
ALTER TABLE `plano_oracao`
  ADD PRIMARY KEY (`Id_ora`);

--
-- Índices de tabela `prioridade`
--
ALTER TABLE `prioridade`
  ADD PRIMARY KEY (`Id_prior`);

--
-- Índices de tabela `rede_ministerial`
--
ALTER TABLE `rede_ministerial`
  ADD PRIMARY KEY (`Id`);

--
-- Índices de tabela `situacao_membro`
--
ALTER TABLE `situacao_membro`
  ADD PRIMARY KEY (`Id`);

--
-- Índices de tabela `smtp`
--
ALTER TABLE `smtp`
  ADD PRIMARY KEY (`Id`);

--
-- Índices de tabela `tipo_admissao`
--
ALTER TABLE `tipo_admissao`
  ADD PRIMARY KEY (`Id`);

--
-- Índices de tabela `ufs`
--
ALTER TABLE `ufs`
  ADD PRIMARY KEY (`UniFedSigla`);

--
-- Índices de tabela `userlevelpermissions`
--
ALTER TABLE `userlevelpermissions`
  ADD PRIMARY KEY (`userlevelid`,`tablename`);

--
-- Índices de tabela `userlevels`
--
ALTER TABLE `userlevels`
  ADD PRIMARY KEY (`userlevelid`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`u`),
  ADD KEY `idx-email` (`email`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `agenda`
--
ALTER TABLE `agenda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de tabela `agsolucao`
--
ALTER TABLE `agsolucao`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `ajuda`
--
ALTER TABLE `ajuda`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `audittrail`
--
ALTER TABLE `audittrail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=593;

--
-- AUTO_INCREMENT de tabela `bancos`
--
ALTER TABLE `bancos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `bens_patrimoniais`
--
ALTER TABLE `bens_patrimoniais`
  MODIFY `Id_Patri` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `cargoscelulas`
--
ALTER TABLE `cargoscelulas`
  MODIFY `id_cgcelula` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `cargosministeriais`
--
ALTER TABLE `cargosministeriais`
  MODIFY `id_cgm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de tabela `cartas`
--
ALTER TABLE `cartas`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `celulas`
--
ALTER TABLE `celulas`
  MODIFY `Id_celula` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `contatos`
--
ALTER TABLE `contatos`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `conta_bancaria`
--
ALTER TABLE `conta_bancaria`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `controle_tarefas`
--
ALTER TABLE `controle_tarefas`
  MODIFY `Id_tarefas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `dias_semana`
--
ALTER TABLE `dias_semana`
  MODIFY `Id_semana` tinyint(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `escolaridade`
--
ALTER TABLE `escolaridade`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `estado_patrimonio`
--
ALTER TABLE `estado_patrimonio`
  MODIFY `Id_est_patri` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `estudos_biblicos`
--
ALTER TABLE `estudos_biblicos`
  MODIFY `Id_estu_bb` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `eventos`
--
ALTER TABLE `eventos`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `financeiro`
--
ALTER TABLE `financeiro`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT de tabela `fin_centro_de_custo`
--
ALTER TABLE `fin_centro_de_custo`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `fin_conta_caixa`
--
ALTER TABLE `fin_conta_caixa`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `fin_forma_pgto`
--
ALTER TABLE `fin_forma_pgto`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `fin_situacao`
--
ALTER TABLE `fin_situacao`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `funcoes_exerce`
--
ALTER TABLE `funcoes_exerce`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de tabela `igrejas`
--
ALTER TABLE `igrejas`
  MODIFY `Id_igreja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `lista_videos`
--
ALTER TABLE `lista_videos`
  MODIFY `Id_video` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `membro`
--
ALTER TABLE `membro`
  MODIFY `Id_membro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT de tabela `meses`
--
ALTER TABLE `meses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `modelo_igreja`
--
ALTER TABLE `modelo_igreja`
  MODIFY `Id` tinyint(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de tabela `plano_oracao`
--
ALTER TABLE `plano_oracao`
  MODIFY `Id_ora` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `prioridade`
--
ALTER TABLE `prioridade`
  MODIFY `Id_prior` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `rede_ministerial`
--
ALTER TABLE `rede_ministerial`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `situacao_membro`
--
ALTER TABLE `situacao_membro`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `smtp`
--
ALTER TABLE `smtp`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tipo_admissao`
--
ALTER TABLE `tipo_admissao`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `u` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44253583;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
