// ***********************************************
// This example commands.js shows you how to
// create various custom commands and overwrite
// existing commands.
//
// For more comprehensive examples of custom
// commands please read more here:
// https://on.cypress.io/custom-commands
// ***********************************************
//
//
// -- This is a parent command --
// Cypress.Commands.add('login', (email, password) => { ... })
//
//
// -- This is a child command --
// Cypress.Commands.add('drag', { prevSubject: 'element'}, (subject, options) => { ... })
//
//
// -- This is a dual command --
// Cypress.Commands.add('dismiss', { prevSubject: 'optional'}, (subject, options) => { ... })
//
//
// -- This will overwrite an existing command --
// Cypress.Commands.overwrite('visit', (originalFn, url, options) => { ... })

Cypress.Commands.add(
  "createBreaking",
  ({ postTitle = "Breaking", customTitle = "", expire = "" }) => {
    cy.createPost({
      postType: "post",
      title: postTitle,
      status: "publish",
    });

    cy.get(".editor-post-publish-panel__header button").click();

    cy.get("#toptalbn_is_breaking").click();

    if ("" !== customTitle) {
      cy.get("#toptalbn_custom_title").click().clear().type(customTitle);
    }

    cy.get(".editor-post-publish-button").click();
  }
);
