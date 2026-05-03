# Deploy — Stand Cars

## Passos para instalar no servidor

1. **Suba os ficheiros** para a raiz do seu domínio (ex: /var/www/html/ ou /opt/lampp/htdocs/)

2. **Instala as dependências** (precisa de Composer instalado):
   ```
   composer install --no-dev --optimize-autoloader
   ```

3. **Configura o banco de dados** em `config/constants.php`:
   - DB_HOST, DB_NAME, DB_USER, DB_PASS

4. **Configura as URLs** em `config/constants.php`:
   - URL_BASE → '' (se estiver na raiz do domínio)
   - URL_PRODUCAO → 'https://seu-dominio.com'
   - APP_ENV → 'production'
   - DEBUG → false   ← IMPORTANTE: nunca true em produção!

5. **Aponta o DocumentRoot do Apache** para a pasta `public/`:
   ```apache
   DocumentRoot /caminho/para/stand-cars/public
   ```
   Ou usa o .htaccess já incluído se estiver numa subpasta.

6. **Permissões de escrita**:
   ```
   chmod -R 755 public/uploads/
   chmod -R 755 storage/
   ```

7. **Cria o utilizador admin** via SQL:
   ```sql
   INSERT INTO usuarios (nome, email, senha, perfil)
   VALUES ('Admin', 'admin@saeldauto.com', '<hash_bcrypt>', 'Administrador');
   ```
   Gera o hash com: php -r "echo password_hash('SuaSenha123!', PASSWORD_DEFAULT);"

## Diferenças dev → produção
| Ficheiro | Dev | Produção |
|---|---|---|
| constants.php APP_ENV | development | production |
| constants.php DEBUG | true | **false** |
| constants.php URL_BASE | /stand-cars/public | '' |
