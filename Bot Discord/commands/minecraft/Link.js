const Discord = require("discord.js");
const { MessageButton, fs, Database } = require("../../index");
const disbut = require('discord-buttons')
var phpUnserialize = require('php-unserialize');

module.exports.run = async (Client, message, args) => {
    if(!message.guild) return;
    if(!args[0]) return message.reply("Vous n'avez préciser le pseudo du joueur")
    if(!args[1]) return message.reply("Vous n'avez préciser le code de liaison associé à votre joueur")

    let sql = `SELECT * FROM discord WHERE player = '${args[0]}'`;
    Database.query(sql, (error, results, fields) => {
      if (error) {
        return console.error(error.message);
      }

      if(results[0]){
        if(results[0]["code"] == args[1]){
          let DELETE = `DELETE FROM discord WHERE player = '${args[0]}'`;
          Database.query(DELETE, (error, results, fields) => {
            if (error) {
              return console.error(error.message);
            }
          });

          let UPDATE = `UPDATE players SET discordId='${message.author.id}' WHERE player = '${args[0]}'`;
          Database.query(UPDATE, (error, results, fields) => {
            if (error) {
              return console.error(error.message);
            }
          });
          

          return message.channel.send("Vous venez d'associer votre compte discord avec le joueur `"+args[0]+"`")
        }else{
          return message.channel.send("Le code de liaison est incorect, l'avez vous mal écris ?")
        }
      }else{
          return message.channel.send("Le joueur n'a demandé aucune liaison de compte")
      }
    });
}