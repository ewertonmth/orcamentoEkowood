# 📊 Sistema de Orçamentos - PHP

Este projeto é um **sistema de geração de orçamentos** desenvolvido em **PHP**, com suporte a exportação em **PDF** utilizando a biblioteca [Dompdf](https://github.com/dompdf/dompdf).  
Foi criado para facilitar o cadastro de produtos, geração de orçamentos e emissão de relatórios profissionais para clientes.

---

## 🚀 Funcionalidades

- Cadastro e gerenciamento de produtos  
- Criação de orçamentos com múltiplos itens  
- Resumo automático do orçamento  
- Exportação em **PDF estilizado**  
- Interface simples e responsiva  

---

## 🛠️ Tecnologias Utilizadas

- **PHP 7.4+**
- **Composer**
- **Dompdf** (geração de PDFs)
- **HTML5 / CSS3**
- **JavaScript (básico)**

---

## 📂 Estrutura de Pastas

```
orcamento-php/
│── index.php              # Página inicial
│── produtos.php           # Cadastro de produtos
│── lista_produtos.php     # Listagem de produtos
│── resumo.php             # Resumo do orçamento
│── exportar_pdf.php       # Exportação de orçamento em PDF
│── composer.json          # Dependências do projeto
│── assets/                # Arquivos estáticos (CSS, imagens)
│   ├── style.css
│   └── images/
│── vendor/                # Bibliotecas instaladas pelo Composer
```

---

## ⚙️ Instalação e Configuração

1. **Clonar este repositório**:
   ```bash
   git clone https://github.com/seu-usuario/orcamento-php.git
   cd orcamento-php
   ```

2. **Instalar as dependências via Composer**:
   ```bash
   composer install
   ```

3. **Configurar servidor local** (ex.: XAMPP, Laragon ou PHP embutido):
   ```bash
   php -S localhost:8000
   ```

4. Acessar no navegador:
   ```
   http://localhost:8000
   ```

---

## 📑 Como Usar

1. Acesse `produtos.php` para cadastrar produtos.  
2. Monte seu orçamento a partir dos produtos cadastrados.  
3. Veja o resumo do orçamento em `resumo.php`.  
4. Exporte o orçamento em PDF através de `exportar_pdf.php`.  

---

## 🤝 Contribuição

Sinta-se à vontade para abrir **issues** e enviar **pull requests**.  
Sugestões e melhorias são sempre bem-vindas!

---

## 📜 Licença

Este projeto está licenciado sob a licença **MIT**.  
Consulte o arquivo [LICENSE](LICENSE) para mais informações.
