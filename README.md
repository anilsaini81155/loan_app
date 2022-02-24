Repo Link -> https://github.com/anilsaini81155/loan_app

Steps to execute the project
1)Put the DB credentials in the env.
2)Run the DB Queries from root dir of proj mini_proj.sql.
3)Run the optimize command , php artisan optimize:clear
4)Run the serve command on port 8050 , php artisan serve --port=8050 (port no upto user)
5)Postman Collection.

Steps to use the APIS.
1)loanAppFirstStep => This API accepts the mobile no and name as input , this gives token as as an response.
   Token validity is of 15 mins. 
   Same token has to be used for rest of the APIs loanAppSecondStep , loanAppRepaymentStep , loanAppViewRepaymentStep.
   Once the token is expired user again needs to generate the token.

2)loanAppSecondStep => This API accepts the loan amount , loan tenure and token.

3)loanAppRepaymentStep => This APIs accepts the emi id , emi amount and token.

4)loanAppViewRepaymentStep => This accepts the loan id and token.