const { Client, Prefix } = require("../../index");

Client.on("message", Message => {
    if (!Message.guild) return;
    if (Message.author.bot) return;

    const Arguments = Message.content.slice(Prefix.length).trim().split(/ +/g);
    const command = Arguments.shift().toLowerCase();
    const cmd = Client.commands.get(command);

    return cmd.run(Client, Message, Arguments);
});