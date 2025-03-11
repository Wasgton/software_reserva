# Sistema de Gestão de Propriedades

## Sobre o Projeto

O projeto é um sistema de gestão para reservas de imóveis de luxo e exclusivos, inspirado no modelo Airbnb. O sistema atua como backoffice para gestão de propriedades, reservas, hóspedes e finanças, com futura expansão para uma API que se conectará ao frontend cliente.

## Tecnologias Utilizadas

- PHP 8.4
- Laravel 11
- PostgreSQL
- Redis (Cache e Filas)
- Tailwind CSS
- DomPDF
- Laravel Excel

## Arquitetura

O projeto segue os seguintes padrões e práticas:

- Service Repository Pattern
- Clean Code
- Cache Strategy
- Queue Jobs
- Form Requests para validação
- Políticas de ACL
- Exportação de relatórios em PDF e Excel

## Módulos do Sistema

### 1. Dashboard
- Visão geral das reservas
- Indicadores financeiros
- Taxa de ocupação
- Resumo de reservas ativas e futuras

### 2. Gestão de Propriedades
- Cadastro e edição de imóveis
- Controle de disponibilidade
- Gestão de valores e comissões
- Filtros por localização e status

### 3. Gestão de Proprietários
- Cadastro completo de proprietários
- Histórico de propriedades
- Relatórios financeiros
- Gestão de repasses

### 4. Gestão de Hóspedes
- Cadastro detalhado de hóspedes
- Histórico de estadias
- Documentação completa
- Busca avançada

### 5. Gestão de Reservas
- Criação e edição de reservas
- Controle de check-in/check-out
- Cálculo automático de valores
- Histórico de alterações

### 6. Gestão Financeira
- Fluxo de caixa
- Controle de receitas e despesas
- Gestão de comissões
- Relatórios gerenciais

### 7. Relatórios
- Relatórios para proprietários
- Exportação em PDF e Excel
- Demonstrativos financeiros
- Histórico de repasses

## Instalação

1. Clone o repositório:
```bash
git clone https://github.com/wasgton/sistema-gestao-propriedades.git
cd sistema-gestao-propriedades
```

2. Instale as dependências:
```bash
composer install
npm install
```

3. Configure o ambiente:
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure o banco de dados no arquivo .env:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=propriedades
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

5. Configure o Redis no arquivo .env:
```env
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

6. Execute as migrações:
```bash
php artisan migrate
```

7. Compile os assets:
```bash
npm run dev
```

8. Inicie o servidor:
```bash
php artisan serve
```

## Jobs e Filas

O sistema utiliza jobs em background para processar:

- Notificações de novas reservas
- Geração de relatórios
- Cálculos financeiros
- Envio de emails

Configuração das filas no supervisor:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/project/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=forge
numprocs=8
redirect_stderr=true
stdout_logfile=/path/to/project/worker.log
stopwaitsecs=3600
```

## Roadmap

### Versão 1.1
- [ ] Integração com gateway de pagamento
- [ ] API REST para frontend
- [ ] Dashboard em tempo real
- [ ] App mobile para proprietários

### Versão 1.2
- [ ] Sistema de avaliações
- [ ] Integração com calendários externos
- [ ] Chat interno
- [ ] Sistema de multas e taxas extras

### Versão 1.3
- [ ] Marketplace de serviços
- [ ] Sistema de fidelidade
- [ ] Integração com channel managers
- [ ] Relatórios personalizados
