-- ============================================
-- Script de Dados de Exemplo - Paulimane
-- Categorias e Produtos para Materiais de Construção
-- ============================================

-- Limpar dados existentes (opcional - comentar se não quiser apagar)
-- DELETE FROM Destaques;
-- DELETE FROM Produtos;
-- DELETE FROM Categoria;

-- ============================================
-- CATEGORIAS
-- ============================================

INSERT INTO Categoria (Imagem, Nome, Descricao) VALUES
('backoffice/uploads/catalogo/calhas.jpg', 'Calhas e Algerozes', 'Sistemas de drenagem pluvial para telhados e coberturas'),
('backoffice/uploads/catalogo/torneiras.jpg', 'Torneiras e Misturadoras', 'Torneiras, misturadoras e acessórios para casa de banho e cozinha'),
('backoffice/uploads/catalogo/tubos.jpg', 'Tubos e Conexões', 'Tubagens em PVC, cobre e multicamada para água e esgoto'),
('backoffice/uploads/catalogo/portas.jpg', 'Portas e Janelas', 'Portas interiores, exteriores e janelas em diversos materiais'),
('backoffice/uploads/catalogo/sanitarios.jpg', 'Sanitários', 'Louças sanitárias, bases de duche e banheiras'),
('backoffice/uploads/catalogo/ferragens.jpg', 'Ferragens e Fechaduras', 'Dobradiças, fechaduras, puxadores e acessórios'),
('backoffice/uploads/catalogo/revestimentos.jpg', 'Revestimentos', 'Azulejos, mosaicos e revestimentos para paredes e pavimentos'),
('backoffice/uploads/catalogo/aquecimento.jpg', 'Aquecimento', 'Radiadores, caldeiras e sistemas de aquecimento central'),
('backoffice/uploads/catalogo/iluminacao.jpg', 'Iluminação', 'Luminárias, candeeiros e sistemas de iluminação LED'),
('backoffice/uploads/catalogo/ferramentas.jpg', 'Ferramentas', 'Ferramentas manuais e elétricas para construção');

-- ============================================
-- PRODUTOS - CALHAS E ALGEROZES
-- ============================================

INSERT INTO Produtos (Imagem, Nome, Descricao, CategoriaID) VALUES
('backoffice/uploads/produtos/calha-pvc-branca.jpg', 'Calha PVC Branca 3m', 'Calha em PVC branco de alta resistência, 125mm x 3m', 1),
('backoffice/uploads/produtos/calha-pvc-castanha.jpg', 'Calha PVC Castanha 3m', 'Calha em PVC castanho, ideal para telhados tradicionais, 125mm x 3m', 1),
('backoffice/uploads/produtos/tubo-queda-pvc.jpg', 'Tubo de Queda PVC 3m', 'Tubo de queda em PVC para escoamento pluvial, Ø90mm x 3m', 1),
('backoffice/uploads/produtos/suporte-calha.jpg', 'Suporte para Calha', 'Suporte metálico galvanizado para fixação de calhas', 1),
('backoffice/uploads/produtos/calha-aluminio.jpg', 'Calha Alumínio 3m', 'Calha em alumínio lacado, resistente à corrosão, 150mm x 3m', 1);

-- ============================================
-- PRODUTOS - TORNEIRAS E MISTURADORAS
-- ============================================

INSERT INTO Produtos (Imagem, Nome, Descricao, CategoriaID) VALUES
('backoffice/uploads/produtos/torneira-loucas.jpg', 'Torneira Monocomando Lavatório', 'Torneira monocomando cromada para lavatório, design moderno', 2),
('backoffice/uploads/produtos/misturadora-banheira.jpg', 'Misturadora Banheira Cromada', 'Misturadora termostática para banheira com duche de mão', 2),
('backoffice/uploads/produtos/torneira-cozinha.jpg', 'Torneira Cozinha Extraível', 'Torneira monocomando para cozinha com duche extraível', 2),
('backoffice/uploads/produtos/torneira-bide.jpg', 'Torneira Monocomando Bidé', 'Torneira monocomando cromada para bidé', 2),
('backoffice/uploads/produtos/coluna-duche.jpg', 'Coluna de Duche Termostática', 'Coluna de duche com misturadora termostática e duche de mão', 2),
('backoffice/uploads/produtos/torneira-jardim.jpg', 'Torneira de Jardim', 'Torneira de jardim em latão cromado, rosca 3/4"', 2);

-- ============================================
-- PRODUTOS - TUBOS E CONEXÕES
-- ============================================

INSERT INTO Produtos (Imagem, Nome, Descricao, CategoriaID) VALUES
('backoffice/uploads/produtos/tubo-pvc-32.jpg', 'Tubo PVC Ø32mm 3m', 'Tubo em PVC rígido para esgoto, Ø32mm x 3m', 3),
('backoffice/uploads/produtos/tubo-pvc-40.jpg', 'Tubo PVC Ø40mm 3m', 'Tubo em PVC rígido para esgoto, Ø40mm x 3m', 3),
('backoffice/uploads/produtos/tubo-pvc-110.jpg', 'Tubo PVC Ø110mm 3m', 'Tubo em PVC para esgoto principal, Ø110mm x 3m', 3),
('backoffice/uploads/produtos/tubo-cobre.jpg', 'Tubo Cobre Ø15mm 3m', 'Tubo em cobre para água quente e fria, Ø15mm x 3m', 3),
('backoffice/uploads/produtos/tubo-multicamada.jpg', 'Tubo Multicamada Ø16mm 50m', 'Tubo multicamada em rolo para água, Ø16mm x 50m', 3),
('backoffice/uploads/produtos/cotovelo-pvc.jpg', 'Cotovelo PVC 90° Ø40mm', 'Cotovelo em PVC a 90 graus para esgoto', 3),
('backoffice/uploads/produtos/te-pvc.jpg', 'Tê PVC Ø40mm', 'Tê em PVC para derivações de esgoto', 3);

-- ============================================
-- PRODUTOS - PORTAS E JANELAS
-- ============================================

INSERT INTO Produtos (Imagem, Nome, Descricao, CategoriaID) VALUES
('backoffice/uploads/produtos/porta-interior-branca.jpg', 'Porta Interior Lacada Branca', 'Porta interior em madeira lacada branca, 80x210cm', 4),
('backoffice/uploads/produtos/porta-entrada-madeira.jpg', 'Porta Entrada Madeira Maciça', 'Porta de entrada em madeira maciça de carvalho, 90x210cm', 4),
('backoffice/uploads/produtos/janela-pvc-branco.jpg', 'Janela PVC Branco 2 Folhas', 'Janela em PVC branco com vidro duplo, 120x120cm', 4),
('backoffice/uploads/produtos/porta-correr-vidro.jpg', 'Porta de Correr Vidro', 'Porta de correr em vidro temperado com perfil alumínio, 100x210cm', 4),
('backoffice/uploads/produtos/janela-aluminio.jpg', 'Janela Alumínio Basculante', 'Janela basculante em alumínio lacado, 60x60cm', 4);

-- ============================================
-- PRODUTOS - SANITÁRIOS
-- ============================================

INSERT INTO Produtos (Imagem, Nome, Descricao, CategoriaID) VALUES
('backoffice/uploads/produtos/sanita-suspensa.jpg', 'Sanita Suspensa Branca', 'Sanita suspensa em cerâmica branca com assento soft-close', 5),
('backoffice/uploads/produtos/lavatorio-coluna.jpg', 'Lavatório com Coluna', 'Lavatório em cerâmica branca com coluna, 60cm', 5),
('backoffice/uploads/produtos/bide-branco.jpg', 'Bidé Cerâmica Branco', 'Bidé em cerâmica branca de alta qualidade', 5),
('backoffice/uploads/produtos/base-duche-resina.jpg', 'Base de Duche Resina 80x80', 'Base de duche em resina antiderrapante, 80x80cm', 5),
('backoffice/uploads/produtos/banheira-acrilico.jpg', 'Banheira Acrílico 170cm', 'Banheira em acrílico reforçado, 170x70cm', 5),
('backoffice/uploads/produtos/lavatorio-encastrar.jpg', 'Lavatório Encastrar Oval', 'Lavatório oval para encastrar em bancada, 50cm', 5);

-- ============================================
-- PRODUTOS - FERRAGENS E FECHADURAS
-- ============================================

INSERT INTO Produtos (Imagem, Nome, Descricao, CategoriaID) VALUES
('backoffice/uploads/produtos/fechadura-seguranca.jpg', 'Fechadura Segurança 3 Pontos', 'Fechadura de segurança com 3 pontos de fecho', 6),
('backoffice/uploads/produtos/dobradica-inox.jpg', 'Dobradiça Inox 100mm', 'Dobradiça em aço inoxidável, 100mm', 6),
('backoffice/uploads/produtos/puxador-aluminio.jpg', 'Puxador Alumínio 300mm', 'Puxador em alumínio anodizado, 300mm', 6),
('backoffice/uploads/produtos/cilindro-europeu.jpg', 'Cilindro Europeu Segurança', 'Cilindro de segurança europeu com 5 chaves', 6),
('backoffice/uploads/produtos/fechadura-wc.jpg', 'Fechadura Casa de Banho', 'Fechadura para casa de banho com indicador', 6);

-- ============================================
-- PRODUTOS - REVESTIMENTOS
-- ============================================

INSERT INTO Produtos (Imagem, Nome, Descricao, CategoriaID) VALUES
('backoffice/uploads/produtos/azulejo-branco-brilho.jpg', 'Azulejo Branco Brilho 20x20', 'Azulejo cerâmico branco brilhante, 20x20cm, caixa 1m²', 7),
('backoffice/uploads/produtos/azulejo-imitacao-madeira.jpg', 'Azulejo Imitação Madeira', 'Azulejo porcelânico imitação madeira, 20x120cm, caixa 1,44m²', 7),
('backoffice/uploads/produtos/mosaico-vidro.jpg', 'Mosaico Vidro Mix', 'Mosaico em vidro tons mistos, 30x30cm, caixa 0,09m²', 7),
('backoffice/uploads/produtos/azulejo-exterior.jpg', 'Azulejo Exterior Antiderrapante', 'Azulejo para exterior antiderrapante, 30x30cm, caixa 1m²', 7);

-- ============================================
-- PRODUTOS - AQUECIMENTO
-- ============================================

INSERT INTO Produtos (Imagem, Nome, Descricao, CategoriaID) VALUES
('backoffice/uploads/produtos/radiador-aluminio.jpg', 'Radiador Alumínio 600mm', 'Radiador em alumínio branco, 600mm altura, 10 elementos', 8),
('backoffice/uploads/produtos/caldeira-mural.jpg', 'Caldeira Mural Condensação', 'Caldeira mural a gás de condensação, 24kW', 8),
('backoffice/uploads/produtos/termostato-digital.jpg', 'Termostato Digital Programável', 'Termostato digital com programação semanal', 8),
('backoffice/uploads/produtos/toalheiro-eletrico.jpg', 'Toalheiro Elétrico 500W', 'Toalheiro elétrico cromado, 500W, 120x60cm', 8);

-- ============================================
-- PRODUTOS - ILUMINAÇÃO
-- ============================================

INSERT INTO Produtos (Imagem, Nome, Descricao, CategoriaID) VALUES
('backoffice/uploads/produtos/lampada-led-e27.jpg', 'Lâmpada LED E27 12W', 'Lâmpada LED E27 12W luz branca quente 3000K', 9),
('backoffice/uploads/produtos/foco-led-teto.jpg', 'Foco LED Encastrar Teto', 'Foco LED encastrável para teto, 7W, branco', 9),
('backoffice/uploads/produtos/candeeiro-teto.jpg', 'Candeeiro Teto Moderno', 'Candeeiro de teto em metal e vidro, design moderno', 9),
('backoffice/uploads/produtos/projetor-led-exterior.jpg', 'Projetor LED Exterior 50W', 'Projetor LED para exterior IP65, 50W', 9);

-- ============================================
-- PRODUTOS - FERRAMENTAS
-- ============================================

INSERT INTO Produtos (Imagem, Nome, Descricao, CategoriaID) VALUES
('backoffice/uploads/produtos/berbequim-bateria.jpg', 'Berbequim Bateria 18V', 'Berbequim sem fios 18V com 2 baterias e maleta', 10),
('backoffice/uploads/produtos/martelo-carpinteiro.jpg', 'Martelo Carpinteiro 500g', 'Martelo de carpinteiro com cabo em fibra, 500g', 10),
('backoffice/uploads/produtos/nivel-laser.jpg', 'Nível Laser Autonivelante', 'Nível laser autonivelante com tripé', 10),
('backoffice/uploads/produtos/alicate-universal.jpg', 'Alicate Universal 200mm', 'Alicate universal isolado 1000V, 200mm', 10),
('backoffice/uploads/produtos/serra-circular.jpg', 'Serra Circular 1200W', 'Serra circular elétrica 1200W com disco 185mm', 10);

-- ============================================
-- Verificar inserções
-- ============================================

SELECT 'Categorias inseridas:' as Info, COUNT(*) as Total FROM Categoria;
SELECT 'Produtos inseridos:' as Info, COUNT(*) as Total FROM Produtos;
SELECT 'Produtos por categoria:' as Info, c.Nome as Categoria, COUNT(p.ID) as Total 
FROM Categoria c 
LEFT JOIN Produtos p ON c.ID = p.CategoriaID 
GROUP BY c.ID, c.Nome;
