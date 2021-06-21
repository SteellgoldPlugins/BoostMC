const { Client, Prefix } = require("../../index");
const { joinVoiceChannel } = require('@discordjs/voice');

Client.on("message", Message => {
    if (!Message.guild) return;
    if (Message.author.bot) return;

    const Arguments = Message.content.slice(Prefix.length).trim().split(/ +/g);
    const command = Arguments.shift().toLowerCase();
    const cmd = Client.commands.get(command);

    if(cmd){
        return cmd.run(Client, Message, Arguments);
    }else{
        if(Message.channel.id == "855318200996069376"){
            Message.react("✅")
            Message.react("❌")
        }
    }
});

Client.on('clickButton', async (button) => {
    if (button.clicker.member == null) await button.clicker.fetch()

    switch(button.id){
        case "accept_rules_button":
            button.clicker.member.roles.add(button.clicker.member.guild.roles.cache.find(r => r.name === "Joueur"))
            button.clicker.member.roles.remove(button.clicker.member.guild.roles.cache.find(r => r.name === "Non-verifié"))
            break;
        case "reject_rules_button":
            button.clicker.member.kick("L'utilisateur n'a pas approuvé le règlement")
            break;
        case "role_news_button":
            if(button.clicker.member.roles.cache.find(r => r.name === "Notifications: Annonces")) {
                button.clicker.member.roles.remove(button.clicker.member.guild.roles.cache.find(r => r.name === "Notifications: Annonces"))
                console.log(button.clicker.member.user.username + "  has removed from Notifications: Annonces")
            }else{
                button.clicker.member.roles.add(button.clicker.member.guild.roles.cache.find(r => r.name === "Notifications: Annonces"))
                console.log(button.clicker.member.user.username + "  has added to Notifications: Annonces")
            }
            break;
        case "role_events_button":
            if(button.clicker.member.roles.cache.find(r => r.name === "Notifications: Events")) {
                button.clicker.member.roles.remove(button.clicker.member.guild.roles.cache.find(r => r.name === "Notifications: Events"))
                console.log(button.clicker.member.user.username + "  has removed from Notifications: Events")
            }else{
                button.clicker.member.roles.add(button.clicker.member.guild.roles.cache.find(r => r.name === "Notifications: Events"))
                console.log(button.clicker.member.user.username + "  has added to Notifications: Events")
            }
            break;
        case "delete_message":
            console.log(button.message.delete())
            break;
    }
    button.defer();
});

Client.on('guildMemberAdd', async (member) => {
    member.guild.channels.cache.get('854590091211964417').send("Bienvenue à <@"+member.user.id+"> sur BoostMC <:boostmc:854590975363907604>");
});