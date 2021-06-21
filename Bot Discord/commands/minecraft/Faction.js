const Discord = require("discord.js");
const { MessageButton, fs, Database } = require("../../index");
const disbut = require('discord-buttons')
var phpUnserialize = require('php-unserialize');

module.exports.run = async (Client, message, args) => {
    if(!message.guild) return;
    if(!args[0]) return message.reply("Vous n'avez préciser aucun nom de faction")

    const ROLES = {
      0: "Recrue",
      1: "Membre",
      2: "Officier",
      3: "Chef"
    };

    let sql = `SELECT * FROM faction WHERE faction = '${args[0]}'`;
    Database.query(sql, (error, results, fields) => {
      if (error) {
        return console.error(error.message);
      }

      var item;
      exports.players = " ";

      members = new Buffer.from(results[0]["players"],"base64");
      const arrayMembers = phpUnserialize.unserialize(members);
      membersRoles = new Buffer.from(results[0]["roles"],"base64");
      const arrayRolesMembers = phpUnserialize.unserialize(membersRoles);

      for (key in arrayMembers) {
        exports.players += "`" + arrayMembers[key] + " ( " + ROLES[arrayRolesMembers[arrayMembers[key]]] + ")`,"
      }

      let embed = new Discord.MessageEmbed();
      embed.setTitle("Faction: " + results[0]["faction"])
      embed.setDescription("Description: " + new Buffer.from(results[0]["description"],"base64"))
      embed.addField("Joueurs dans la faction", exports.players)
      embed.addField("Puissance",results[0]["power"],true)
      embed.addField("Argent", results[0]["money"]+"$", true)
      embed.addField("Ouverte:", "Sous-invitations", true)
      embed.setColor("#5b7cde")
      // embed.setImage("https://via.placeholder.com/500x80?text="+(args[0]).replace(" ","+"))
      //embed.setThumbnail("https://via.placeholder.com/500x500?text="+(args[0]).replace(" ","+"))
      embed.setThumbnail("https://media.discordapp.net/attachments/854273062769655818/854398593301741568/image0.jpg?width=716&height=716");
      embed.setImage("https://media.discordapp.net/attachments/854273062769655818/854398605075021825/image1.jpg?width=1214&height=683");
      embed.setTimestamp()
      embed.setFooter("Bêta")

      let scoreboardFactions = new disbut.MessageButton()
        .setStyle('blurple')
        .setEmoji('🧩')
        .setLabel('Classement des factions !') 
        .setID('view_scoreboard_factions') 
        .setDisabled(false);
      let deleteMessage = new disbut.MessageButton()
        .setStyle('red')
        .setEmoji('❌')
        .setLabel('Supprimer le message !') 
        .setID('delete_message') 
        .setDisabled(false);


      message.channel.send("", {
        button: [scoreboardFactions, deleteMessage],
        embed: embed
      });
    });
}