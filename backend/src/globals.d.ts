declare module 'bcryptjs' {
  function hash(data: string | Buffer, saltOrRounds: string | number): Promise<string>
  function compare(data: string | Buffer, encrypted: string): Promise<boolean>
  function genSalt(rounds?: number): Promise<string>
  function getRounds(encrypted: string): number
}
