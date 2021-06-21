const Discord = require("discord.js");
const { MessageButton, fs } = require("../index");
const disbut = require('discord-buttons')

module.exports.run = async (Client, message, args) => {
    if(!message.guild) return;
    if(!message.member.roles.cache.find(r => r.name === "Administration")) return message.channel.send("Vous n'avez pas les permissions requises")
    
    let buttonAccept = new disbut.MessageButton()
      .setStyle('green')
      .setLabel('J\'accèpte !') 
      .setID('accept_rules_button') 
      .setDisabled(false);
    let buttonReject = new disbut.MessageButton()
      .setStyle('red')
      .setLabel('Je refuse !') 
      .setID('reject_rules_button') 
      .setDisabled(false);

    message.channel.send("__**Règles liées au tchat**__"+
        "\n・Toute **pollution** (abusive ou non) quelque ce soit le tchat sera sanctionnée. Que ce soit du **flood**, des **majuscules** ou encore du **spam**"+
        "\n・Il est interdit **d'insulter** autrui ou de lui porter mépris. Il est aussi interdit **d'inciter à la haine** ou de tenir des **propos déplacés** sous une quelconque forme"+
        "\n・La mauvaise utilisation des **salons textuels** mis à votre disposition sera sanctionnée"+
        "\n・Il est interdit de tenir des propos dans une **autre langue** que le français ou l'anglais"+
        "\n・Les demandes de don à caractère **mendiantes** sont prohibées et se verront sanctionnées"+
        "\n・Faire de la **publicité** pour un quelconque média/serveur externe est interdite si la permission ne vous a pas été accordée ou que vous êtes **partenaire** du serveur"+
        "\n・Tout **manquement de respect** à une personne soutenant le serveur financièrement est interdite",{ buttons: [buttonAccept, buttonReject ]});
}