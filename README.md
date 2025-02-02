# Loteria - API de Sorteios

API para geração e verificação de bilhetes de loteria.

## 💡 Sobre o Projeto

Esta API permite a geração de bilhetes de loteria, além de realizar sorteios e verificar os resultados. O sistema foi desenvolvido seguindo princípios SOLID, Clean Architecture e boas práticas de programação.

### Funcionalidades

- ✨ Geração de bilhete premiado (6 dezenas entre 01 e 60)
- 🎫 Geração de múltiplos bilhetes (até 50 bilhetes)
- 🔢 Suporte a bilhetes com 6 a 10 dezenas
- 📊 Visualização dos resultados em tabela HTML estilizada
- ✅ Verificação automática de acertos

## 🚀 Tecnologias Utilizadas

- PHP 8.4+
- Docker & Docker Compose
- PHPUnit para testes
- Apache como servidor web

<p style="text-align: left;">
    <img src="https://img.shields.io/badge/PHP-777BB4?logo=php&logoColor=white" />
    <img src="https://img.shields.io/badge/DOCKER-2496ED?logo=docker&logoColor=white" />
    <img src="https://img.shields.io/badge/APACHE-D22128?logo=apache&logoColor=white" />
</p>

## 📋 Requisitos

- Docker
- Docker Compose
- Git

## 🛠️ Instalação

1. Clone o repositório:

```bash
git clone https://github.com/junionestor/lottery.git
cd lottery
```

2. Inicie os containers:

```bash
docker-compose up -d
```

3. Instale as dependências:

```bash
docker-compose exec app composer install
```

## 🏗️ Arquitetura

O projeto segue os princípios da Clean Architecture:

```
src/
├── Domain/           # Regras de negócio e entidades
├── Application/      # Casos de uso da aplicação
├── Infrastructure/   # Controllers e implementações concretas
└── Presentation/     # Visualização (HTML)
```

## 👥 Autor

Nestor Júnio
