const Discord = require("discord.js");
const { MessageButton, fs } = require("../index");
const disbut = require('discord-buttons')

module.exports.run = async (Client, message, args) => {
    if(!message.guild) return;
    if(!message.member.roles.cache.find(r => r.name === "Administration")) return message.channel.send("Vous n'avez pas les permissions requises")
    
    let buttonAccept = new disbut.MessageButton()
      .setStyle('blurple')
      .setEmoji('✨')
      .setLabel('Nouveautées et annonces !') 
      .setID('role_news_button') 
      .setDisabled(false);
    let buttonReject = new disbut.MessageButton()
      .setStyle('blurple')
      .setEmoji('⚡')
      .setLabel('Évènements') 
      .setID('role_events_button') 
      .setDisabled(false);

    message.channel.send("Choisissez vos rôles\n**-** `Nouveautées et annonces`: Vous serrez mentionner lors d'un nouveau message dans <#854273049679495168>\n**-** `Évènements`: Vous serrez mentionner lors ce qu'il y aura des évènements, concours, ou autres (Salon encore indisponible)"+"\n\nTIP: Pour retirer le rôle, re-appuyer sur le bouton correspondant.",
    { 
      buttons: [
        buttonAccept, 
        buttonReject 
      ]
    });
}