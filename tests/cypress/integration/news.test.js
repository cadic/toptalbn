describe("Should be able to add breaking news", () => {
  beforeEach(() => {
    cy.login();
  });

  it("Should create breaking news", () => {
    const title = "Breaking " + Math.random().toString(16).substring(7);
    cy.createBreaking({ postTitle: title });
    cy.wait(1000);
    cy.visit("/");
    cy.get("#toptalbn").should("contain", title);
  });

  it("Should create breaking with custom title", () => {
    const title = "Breaking " + Math.random().toString(16).substring(7);
    const customTitle = "Custom " + Math.random().toString(16).substring(7);
    cy.createBreaking({ postTitle: title, customTitle: customTitle });
    cy.wait(1000);
    cy.visit("/");
    cy.get("#toptalbn")
      .should("contain", customTitle)
      .should("not.contain", title);
  });
});
