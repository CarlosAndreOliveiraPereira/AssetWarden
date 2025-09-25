-- Este script SQL cria o banco de dados e a tabela de usuários necessários para a aplicação.
-- Para usar este script, você pode importá-lo através de uma ferramenta como o phpMyAdmin
-- ou executá-lo diretamente no seu cliente MySQL.

-- 1. Criação do Banco de Dados (se ele não existir)
-- O `IF NOT EXISTS` previne erros caso o banco de dados já tenha sido criado.
CREATE DATABASE IF NOT EXISTS `mysa_db`
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

-- 2. Seleciona o banco de dados recém-criado para as operações seguintes
USE `mysa_db`;

-- 3. Criação da Tabela de Usuários (`usuarios`)
-- Esta tabela armazenará as informações de login dos usuários.
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,          -- Identificador único para cada usuário
  `nome` VARCHAR(255) NOT NULL,                 -- Nome completo do usuário
  `email` VARCHAR(255) NOT NULL UNIQUE,         -- E-mail do usuário, deve ser único
  `senha` VARCHAR(255) NOT NULL,                -- Senha do usuário (será armazenada como hash)
  `data_cadastro` TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Data e hora do cadastro do usuário
) ENGINE=InnoDB;

-- Fim do script. O banco de dados e a tabela estão prontos para serem usados.