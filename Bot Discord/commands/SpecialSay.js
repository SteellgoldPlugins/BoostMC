const Discord = require("discord.js");
const { MessageButton, fs } = require("../index");
const disbut = require('discord-buttons')

module.exports.run = async (Client, message, args) => {
    if(!message.guild) return;
    if(!message.member.roles.cache.find(r => r.name === "Administration")) return message.channel.send("Vous n'avez pas les permissions requises")
    const channel = message.guild.channels.cache.get(args[0]);

    let button = new disbut.MessageButton()
      .setStyle('blurple')
      .setEmoji(args[2])
      .setLabel((args[1]).replace("_"," ")) 
      .setID('ok_say') 
      .setDisabled(false);

    channel.send(args.slice(3).join(" "),
    { 
      buttons: [
        button
      ]
    });
}