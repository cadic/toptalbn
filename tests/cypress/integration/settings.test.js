const { hexToRgb } = require("../support/functions");

describe("Should be able to manage settings", () => {
  beforeEach(() => {
    cy.login();
  });

  it("Should update prefix", () => {
    cy.visit("/wp-admin/options-general.php?page=breaking-news");
    const prefix = "Prefix " + Math.random().toString(32).substring(7) + ":";
    cy.get("#prefix").click().type("{selectAll}").type(prefix);

    cy.get("#submit").click();

    cy.get(".notice-success").should("contain", "Settings saved");
    cy.get("#prefix").should("have.value", prefix);

    const title = "Breaking " + Math.random().toString(16).substring(7);
    cy.createBreaking({ postTitle: title });
    cy.wait(1000);
    cy.visit("/");
    cy.get("#toptalbn").should("contain", prefix);
  });

  it("Should update color and background", () => {
    const color = "#" + Math.floor(Math.random() * 16777215).toString(16);
    const bg = "#" + Math.floor(Math.random() * 16777215).toString(16);
    cy.visit("/wp-admin/options-general.php?page=breaking-news");

    cy.get(".toptalbn-color-wrap .wp-picker-container button").click();
    cy.get("#color").click().clear().type(color);

    cy.get(".toptalbn-background-wrap .wp-picker-container button").click();
    cy.get("#background_color").click().clear().type(bg);

    cy.get("#submit").click();

    cy.get(".notice-success").should("contain", "Settings saved");

    const title = "Breaking " + Math.random().toString(16).substring(7);
    cy.createBreaking({ postTitle: title });
    cy.wait(1000);
    cy.visit("/");
    cy.get("#toptalbn")
      .should("have.css", "background-color", hexToRgb(bg))
      .should("have.css", "color", hexToRgb(color));
  });
});
