### Blog App with Slim Framework

Requirements: ```docker```, ```docker-compose```, ```php```,  ```composer```

Instructions:

1. Clone project,
2. Enter in project folder,
3. Create a ```.env``` file and add this keys:
    * APP_ENV='development'
    * DB_DRIVER='mysql'
    * DB_HOST='0.0.0.0'
    * DB_DATABASE='mpblog'
    * DB_USERNAME='root'
    * DB_PASSWORD='root'
    * DP_CHARSET='utf8'
    * DB_COLLATION='utf8_unicode_ci'
    * DB_PREFIX=''
4. Install dependencies with ```composer install```,
5. Execute ```docker-composer up -d``` to start database,
6. Start app with command: ```php -S 0.0.0.0:8000 -t public/```,
7. Open in browser: [http://localhost:8000](http://localhost:8000),


##### Melhorias para o projeto:

- [ ] - Criação de migrations
- [x] - Carregar variáveis de ambiente(usando phpdotenv)
- [x] - Mensagens personalizadas nas validações
- [x] - Mostrar aviso de posts vazios na home
- [x] - Melhor forma de mostrar os alerts (local onde os mesmos aparecem)
- [x] - Mudar placeholders em login e registro
- [ ] - Como pegar o old('') como no laravel com slim
- [x] - Verificar data na confirmação do email
- [x] - Informar caso o usuário já tenha confirmado a conta anteriormente
- [x] - Usuários podem ver todos os posts, mas podem editar ou excluir apenas os criados por eles mesmos
- [x] - Admins devem ter permissões de alterar todos os posts
- [x] - Mudar as informações na navbar, como dashboard que deve ser novo post
- [x] - Criar área onde usuário pode ver apenas seus posts
- [x] - Ao criar um post o mesmo deve ser redirecionado para seus posts
- [ ] - Reset de senha para usuário
- [ ] - Troca de senha para usuário
- [x] - Proteger a rota de atualização de avatar
- [x] - Mostrar imagem antes de usar o upload do avatar
- [x] - Ordenação dos posts do mais novo pro mais antigo
- [x] - Ativação de logs na aplicação (Monolog)
- [x] - Páginas de erro personalizadas, 404 e 500 (apenas em produção)
- [ ] - CSRF nos forms