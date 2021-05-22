const Discord = require("discord.js");
const { Database } = require("../../index");

module.exports.run = async (Client, message, args) => {
    if(!message.guild) return;

    if(!args[0]) return message.channel.send("Vous n'avez pas préciser de pseudo d'un joueur")

    Database.each("SELECT * FROM players WHERE player = ?", (args[0]), (err, row) => {
        if (err){
            console.error(err.message);
            return message.channel.send(`Aucun joueur n'existe avec le pseudo **${args[0]}**`)
        }else{
            const embed = new Discord.MessageEmbed()
            embed.setTitle("Informations")
            embed.setDescription(`Voici les informations du joueur **${args[0]}**`)
            embed.addField("() Faction",row.faction, true)
            embed.addField("() Argent",row.money  + "$", true)
            message.channel.send(embed)
        }
    });
}