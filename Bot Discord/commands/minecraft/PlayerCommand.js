const Discord = require("discord.js");
const { Database, fs} = require("../../index");

module.exports.run = async (Client, message, args) => {
    if(!message.guild) return;

    const ROLES = {
        0: "-",
        1: "*",
        2: "**",
        3: "***",
    }

    const RANKS = {
        0: "Joueur",
        1: "Premium",
    }

    if(!args[0]) return message.channel.send("Vous n'avez pas préciser de pseudo d'un joueur")

    Database.each("SELECT * FROM players WHERE player = ?", (args[0]), (err, row) => {
        if (err){
            console.error(err.message);
            return message.channel.send(`Aucun joueur n'existe avec le pseudo **${args[0]}**`)
        }else{
            if(row.faction !== "none") faction = JSON.parse(fs.readFileSync('./../MC/plugin_data/MuseumCore/factions/' + row.faction + '.json','utf8'));

            const embed = new Discord.MessageEmbed()
            embed.setTitle("Informations")
            embed.setDescription(`Voici les informations du joueur **${args[0]}**`)
            embed.addField('Grade', RANKS[row.rank], true)
            embed.addField('Temps de jeu', "1j30m02s", true)
            embed.addField('Compte Discord', row.discordId === "none" ? "Aucun compte lié" : "<@" + row.discordId + ">", true)
            embed.addField('Faction', row.faction === "none" ? "Aucune faction" : faction[row.faction]["name"] + ROLES[row.faction_role], true)
            embed.addField('Argent', row.money + '$', true)
            message.channel.send(embed)
        }
    });
}