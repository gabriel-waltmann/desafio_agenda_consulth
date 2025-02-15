describe('Contact CRUD', () => {
  beforeEach(() => {
    cy.visit('http://localhost:8000/');
    
    cy.wait(1000);
  })

  it('Should open the contact new page, fill the form, save the contact and open the contact page', () => {
    cy.log("open contact new page");
    cy.contains("button", "+ CONTATO")
      .should("be.visible")
      .click();

    cy.log("has page title");
    cy.contains("h1", "Novo contato")
      .should("be.visible");

    cy.log("fill the form");

    cy.log("type the name");
    cy.get("input[placeholder=\"Nome*\"]")
      .should("be.visible")
      .type("John Doe");

    cy.log("type the email");
    cy.get("input[placeholder=\"E-mail*\"]")
      .should("be.visible")
      .type("johndoe@example.com");

    cy.log("country input should be filled");
    cy.get("input[placeholder=\"País\"]")
      .should("be.visible")
      .should("have.value", "Brasil");

    cy.log("type the state");
    cy.get("input[placeholder=\"Estado\"]")
      .should("be.visible")
      .type("Santa Catarina");

    cy.log("type the city");
    cy.get("input[placeholder=\"Cidade\"]")
      .should("be.visible")
      .type("Joinville");

    cy.log("type the neighborhood");
    cy.get("input[placeholder=\"Bairro\"]")
      .should("be.visible")
      .type("Centro");

    cy.log("type the address");
    cy.get("input[placeholder=\"Endereço\"]")
      .should("be.visible")
      .type("Rua 1");

    cy.log("type the zip code");
    cy.get("input[placeholder=\"CEP\"]")
      .should("be.visible")
      .type("12345-678");

    cy.log("country code input should be filled");
    cy.get("input[placeholder=\"Prefixo\"]")
      .should("be.visible")
      .should("have.value", "55");

    cy.log("type the phone");
    cy.get("input[placeholder=\"Número\"]")
      .should("be.visible")
      .type("(11) 99999-9999");

    cy.log("save the contact");
    cy.contains("button", "SALVAR")
      .should("be.visible")
      .click()
      .wait(1000);

    cy.log("is contact page");
    cy.url()
      .should("include", "/contact/");
  });

  it("Should have a contact list, open the contact edit page, include a new phone, save and open the contact page", () => {
    cy.log("has page title");
    cy.contains("h1", "Agenda de contatos")
      .should("be.visible");

    cy.log("has contact list");
    cy.get("ul li")
      .should("be.visible")
      .should("have.length.greaterThan", 0);

    cy.log("open the contact page");
    cy.contains("ul li", "John Doe")
      .should("be.visible")
      .click()
      .wait(1000);

    cy.log("is contact page");
    cy.url()
      .should("include", "/contact/");

    cy.log("open contact edit page");
    cy.contains("button", "EDITAR")
      .should("be.visible")
      .click();

    cy.log("is contact edit page");
    cy.url()
      .should("include", "/edit");

    cy.log("add a new phone");
    cy.contains("button", "+ TELEFONE")
      .should("be.visible")
      .click();

    cy.log("fill the second phone number input");
    cy.get("input[placeholder=\"Número\"]")
      .last()
      .should("be.visible")
      .type("(11) 00000-0000");

    cy.log("save the contact");
    cy.contains("button", "SALVAR")
      .should("be.visible")
      .click()
      .wait(1000);

    cy.log("is contact page");
    cy.url()
      .should("include", "/contact/");
  });

  it("Should open the contact edit page, remove the first added phone, save and open the contact page", () => {
    cy.log("open contact page");
    cy.contains("ul li", "John Doe")
      .should("be.visible")
      .click()
      .wait(1000);

    cy.log("is contact page");
    cy.url()
      .should("include", "/contact/");

    cy.log("open contact edit page");
    cy.contains("button", "EDITAR")
      .should("be.visible")
      .click()
      .wait(1000);

    cy.log("is contact edit page");
    cy.url()
      .should("include", "/edit");

    cy.log("remove the last added phone");
    cy.contains("button", "- TELEFONE")
      .first()
      .should("be.visible")
      .click()
      .wait(1000);

    cy.log("save the contact");
    cy.contains("button", "SALVAR")
      .should("be.visible")
      .click()
      .wait(1000);

    cy.log("is contact page");
    cy.url()
      .should("include", "/contact/");
  });

  it("Should open the contact page, have all fields and a single phone", () => {
    cy.log("open contact page");
    cy.contains("ul li", "John Doe")
      .should("be.visible")
      .click()
      .wait(1000);

    cy.log("is contact page");
    cy.url()
      .should("include", "/contact/");

    cy.log("has page title");
    cy.contains("h1", "Detalhes do contato")
      .should("be.visible");

    cy.log("has all fields");

    cy.log("has name field");
    cy.contains("h2", "Nome: John Doe")
      .should("be.visible");

    cy.log("has email field");
    cy.contains("p", "E-mail: johndoe@example.com")
      .should("be.visible");

    cy.log("has phone field");
    cy.contains("p", "+55 (11) 00000-0000")
      .should("be.visible")
      .should("have.length", 1);

    cy.log("has country field");
    cy.contains("p", "País: Brasil")
      .should("be.visible");

    cy.log("has state field");
    cy.contains("p", "Estado: Santa Catarina")
      .should("be.visible");

    cy.log("has city field");
    cy.contains("p", "Cidade: Joinville")
      .should("be.visible");

    cy.log("has neighborhood field");
    cy.contains("p", "Bairro: Centro")
      .should("be.visible");

    cy.log("has address field");
    cy.contains("p", "Endereço: Rua 1")
      .should("be.visible");
  })

  it("Should open the contact page, delete and open the contact list page", () => {
    cy.log("open contact page");
    cy.contains("ul li", "John Doe")
      .should("be.visible")
      .click()
      .wait(1000);

    cy.log("is contact page");
    cy.url()
      .should("include", "/contact/");

    cy.log("delete the contact");
    cy.contains("button", "DELETAR")
      .should("be.visible")
      .click()
      .wait(1000);

    cy.log("is contact list page");
    cy.url()
      .should("include", "/");
  });
})